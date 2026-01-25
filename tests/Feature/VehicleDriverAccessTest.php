<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleDriverAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $vehicle;
    protected $driverUser;
    protected $vehicleUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a vehicle
        $this->vehicle = Vehicle::factory()->create([
            'plate_number' => 'B1234TST',
        ]);

        // Create a driver user
        $this->driverUser = User::factory()->create(['name' => 'John Driver']);

        // Attach driver to vehicle (Approved)
        $this->vehicleUser = VehicleUser::create([
            'registered_by' => $this->driverUser->id,
            'vehicle_id' => $this->vehicle->id,
            'role_type' => 'personal',
            'driver_name' => 'Mr. Chauffeur',
            'status' => 'approved',
            'evidence_path' => 'dummy.pdf',
            'reviewed_by' => User::factory()->create(['role' => 'admin'])->id,
            'reviewed_at' => now(),
        ]);
    }

    /** @test */
    public function guest_cannot_view_driver_info()
    {
        $response = $this->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_DENIED');
        $response->assertSee('AUTHORIZED_DRIVERS');
        $response->assertDontSee('Mr. Chauffeur');
        $response->assertDontSee('ACCESS_GRANTED');
    }

    /** @test */
    public function regular_user_cannot_view_driver_info()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_DENIED');
        $response->assertDontSee('Mr. Chauffeur');
        
        $this->assertDatabaseCount('audit_logs', 0);
    }

    /** @test */
    public function superadmin_can_view_driver_info()
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_GRANTED');
        $response->assertSee('AUTHORIZED_DRIVERS');
        $response->assertSee('Mr. Chauffeur');
        $response->assertSee('personal'); // Role type (css handles uppercase)
        
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'VIEW_SPECS_DRIVERS',
            'description' => "Viewed specifications and driver info for vehicle {$this->vehicle->plate_number}",
        ]);
    }

    /** @test */
    public function admin_can_view_driver_info()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_GRANTED');
        $response->assertSee('Mr. Chauffeur');
        
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'VIEW_SPECS_DRIVERS',
        ]);
    }

    /** @test */
    public function verified_tier1_user_can_view_driver_info()
    {
        $user = User::factory()->create([
            'role' => 'tier_1',
            'kyc_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_GRANTED');
        $response->assertSee('Mr. Chauffeur');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'VIEW_SPECS_DRIVERS',
        ]);
    }

    /** @test */
    public function unverified_tier1_user_cannot_view_driver_info()
    {
        $user = User::factory()->create([
            'role' => 'tier_1',
            'kyc_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_DENIED');
        $response->assertDontSee('Mr. Chauffeur');
        
        $this->assertDatabaseCount('audit_logs', 0);
    }

    /** @test */
    public function pending_driver_is_not_shown()
    {
        // Create another driver request that is pending
        $pending = VehicleUser::create([
            'registered_by' => User::factory()->create()->id,
            'vehicle_id' => $this->vehicle->id,
            'role_type' => 'taxi',
            'driver_name' => 'Mr. Pending',
            'status' => 'pending',
            'evidence_path' => 'dummy.pdf',
        ]);

        $this->assertEquals('pending', $pending->status);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('Mr. Chauffeur'); // Approved one
        $response->assertDontSee('Mr. Pending'); // Pending one should be filtered out by Controller query
    }
}
