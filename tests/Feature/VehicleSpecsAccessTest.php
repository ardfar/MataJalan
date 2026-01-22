<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleSpecsAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $vehicle;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a vehicle with specs
        $this->vehicle = Vehicle::factory()->create([
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2023,
            'color' => 'Black',
            'vin' => '1234567890ABCDEFG',
        ]);
    }

    /** @test */
    public function guest_cannot_view_specs()
    {
        $response = $this->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_DENIED');
        // The header is visible, but the content is restricted
        $response->assertDontSee('1234567890ABCDEFG'); // VIN
        $response->assertDontSee('ACCESS_GRANTED');
    }

    /** @test */
    public function regular_user_cannot_view_specs()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_DENIED');
        $response->assertDontSee('1234567890ABCDEFG');
        
        $this->assertDatabaseCount('audit_logs', 0);
    }

    /** @test */
    public function superadmin_can_view_specs()
    {
        $user = User::factory()->create(['role' => 'superadmin']);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_GRANTED');
        $response->assertSee('Toyota');
        $response->assertSee('Camry');
        $response->assertSee('1234567890ABCDEFG');
        
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'VIEW_SPECS',
            'description' => "Viewed specifications for vehicle {$this->vehicle->plate_number}",
        ]);
    }

    /** @test */
    public function admin_can_view_specs()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_GRANTED');
        $response->assertSee('1234567890ABCDEFG');
        
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'VIEW_SPECS',
        ]);
    }

    /** @test */
    public function verified_tier1_user_can_view_specs()
    {
        $user = User::factory()->create([
            'role' => 'tier_1',
            'kyc_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_GRANTED');
        $response->assertSee('1234567890ABCDEFG');

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'VIEW_SPECS',
        ]);
    }

    /** @test */
    public function unverified_tier1_user_cannot_view_specs()
    {
        $user = User::factory()->create([
            'role' => 'tier_1',
            'kyc_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('vehicle.show', $this->vehicle->uuid));

        $response->assertStatus(200);
        $response->assertSee('ACCESS_DENIED');
        $response->assertDontSee('1234567890ABCDEFG');
        
        $this->assertDatabaseCount('audit_logs', 0);
    }
}
