<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VehicleUserTest extends TestCase
{
    use RefreshDatabase;

    protected $vehicle;
    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vehicle = Vehicle::factory()->create();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
        Storage::fake('private');
    }

    /** @test */
    public function user_can_view_registration_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('vehicle.user.create', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('User Registration Protocol');
    }

    /** @test */
    public function user_can_submit_registration_request()
    {
        $file = UploadedFile::fake()->create('evidence.pdf', 100);

        $response = $this->actingAs($this->user)
            ->post(route('vehicle.user.store', $this->vehicle->uuid), [
                'role_type' => 'personal',
                'driver_name' => 'John Doe',
                'evidence' => $file,
            ]);

        $response->assertRedirect(route('vehicle.show', $this->vehicle->uuid));
        $this->assertDatabaseHas('vehicle_users', [
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'role_type' => 'personal',
            'driver_name' => 'John Doe',
            'status' => 'pending',
        ]);
        
        // Assert file stored
        $vehicleUser = VehicleUser::first();
        Storage::disk('private')->assertExists($vehicleUser->evidence_path);
    }

    /** @test */
    public function admin_can_list_pending_requests()
    {
        VehicleUser::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'role_type' => 'corporate',
            'driver_name' => 'Jane Smith',
            'evidence_path' => 'path/to/file.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.vehicle-users.index'));

        $response->assertStatus(200);
        $response->assertSee('corporate');
        $response->assertSee('Jane Smith');
    }

    /** @test */
    public function admin_can_approve_request()
    {
        $vehicleUser = VehicleUser::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'role_type' => 'taxi',
            'driver_name' => 'Bob Driver',
            'evidence_path' => 'path/to/file.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.vehicle-users.update', $vehicleUser), [
                'status' => 'approved',
            ]);

        $response->assertRedirect();
        $this->assertEquals('approved', $vehicleUser->fresh()->status);
        $this->assertNotNull($vehicleUser->fresh()->reviewed_at);
        
        // Audit log check
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'REVIEW_VEHICLE_USER',
            'user_id' => $this->admin->id,
        ]);
    }

    /** @test */
    public function non_admin_cannot_review_requests()
    {
        $vehicleUser = VehicleUser::create([
            'user_id' => $this->user->id,
            'vehicle_id' => $this->vehicle->id,
            'role_type' => 'taxi',
            'driver_name' => 'Bob Driver',
            'evidence_path' => 'path/to/file.pdf',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('admin.vehicle-users.update', $vehicleUser), [
                'status' => 'approved',
            ]);

        $response->assertStatus(403);
    }
}
