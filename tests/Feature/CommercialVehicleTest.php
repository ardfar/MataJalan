<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleSpec;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommercialVehicleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_register_new_truck()
    {
        $response = $this->actingAs($this->user)->post(route('vehicle.store', 'B 9000 TRK'), [
            'type' => 'truck',
            'make' => 'Hino',
            'model' => '500 Series',
            'year' => 2024,
            'color' => 'Green',
            'vin' => 'HINO500123456',
        ]);

        $vehicle = Vehicle::where('plate_number', 'B 9000 TRK')->first();
        $this->assertNotNull($vehicle);
        $this->assertEquals('truck', $vehicle->type);
        $this->assertEquals('Hino', $vehicle->make);
        
        $response->assertRedirect(route('vehicle.registered', $vehicle->uuid));
    }

    /** @test */
    public function commercial_specs_affect_threat_level_for_heavy_duty()
    {
        // 1. Create a Heavy Duty Truck Spec (High Risk)
        VehicleSpec::create([
            'type' => 'truck',
            'brand' => 'Hino',
            'model' => '500 Series',
            'variant' => 'Ranger FL 260 JW',
            'category' => 'Heavy Duty Truck',
            'engine_cc' => 7684,
            'horsepower' => 260,
            'torque' => 794,
            'transmission' => 'Manual',
            'fuel_type' => 'Diesel',
            'seat_capacity' => 3,
            'gvwr_kg' => 26000,
        ]);

        // 2. Create a Vehicle matching the spec
        $vehicle = Vehicle::create([
            'plate_number' => 'B 9999 HVY',
            'type' => 'truck',
            'make' => 'Hino',
            'model' => '500 Series',
            'year' => 2023,
            'color' => 'Green',
        ]);

        // 3. Add ratings averaging 2.5
        // Rating 1: 2
        $vehicle->ratings()->create([
            'user_id' => $this->user->id,
            'rating' => 2,
            'comment' => 'Dangerous driving',
            'tags' => ['reckless'],
            'is_honest' => true,
            'status' => 'approved',
        ]);
        
        // Rating 2: 3
        $vehicle->ratings()->create([
            'user_id' => $this->user->id,
            'rating' => 3,
            'comment' => 'Okay',
            'tags' => [],
            'is_honest' => true,
            'status' => 'approved',
        ]);

        // Avg Rating = 2.5.
        // For Heavy Duty Truck: High Threshold is 3.2.
        // 2.5 < 3.2 => HIGH Threat.
        
        // 4. Check WebController Index Logic
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        
        $viewVehicles = $response->viewData('vehicles');
        $processedVehicle = $viewVehicles->firstWhere('plate', 'B 9999 HVY');
        
        $this->assertNotNull($processedVehicle);
        $this->assertEquals('HIGH', $processedVehicle->threatLevel);
    }
}
