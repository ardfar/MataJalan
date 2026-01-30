<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleSpec;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MotorcycleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_register_new_motorcycle()
    {
        $response = $this->actingAs($this->user)->post(route('vehicle.store', 'B 1234 MTR'), [
            'type' => 'motorcycle',
            'make' => 'Honda',
            'model' => 'Vario',
            'year' => 2024,
            'color' => 'Black',
            'vin' => 'MH1VARIO123456',
        ]);

        $vehicle = Vehicle::where('plate_number', 'B 1234 MTR')->first();
        $this->assertNotNull($vehicle);
        $this->assertEquals('motorcycle', $vehicle->type);
        $this->assertEquals('Honda', $vehicle->make);
        
        $response->assertRedirect(route('vehicle.registered', $vehicle->uuid));
    }

    /** @test */
    public function motorcycle_specs_affect_threat_level()
    {
        // 1. Create a Sport Bike Spec (High Risk)
        VehicleSpec::create([
            'type' => 'motorcycle',
            'brand' => 'Kawasaki',
            'model' => 'Ninja ZX-25R',
            'variant' => 'ABS SE',
            'category' => 'Sport',
            'engine_cc' => 250,
            'horsepower' => 51,
            'torque' => 22,
            'transmission' => 'Manual',
            'fuel_type' => 'Bensin',
            'seat_capacity' => 2,
        ]);

        // 2. Create a Vehicle matching the spec
        $vehicle = Vehicle::create([
            'plate_number' => 'B 6666 SPD',
            'type' => 'motorcycle',
            'make' => 'Kawasaki',
            'model' => 'Ninja ZX-25R',
            'year' => 2023,
            'color' => 'Green',
            'vin' => 'MH1NINJA123456',
        ]);

        // 3. Add ratings averaging 2.5
        // Rating 1: 2
        $vehicle->ratings()->create([
            'user_id' => $this->user->id,
            'rating' => 2,
            'comment' => 'Bad driver',
            'tags' => ['speeding'],
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
        
        // 4. Check WebController Index Logic
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        
        $viewVehicles = $response->viewData('vehicles');
        $processedVehicle = $viewVehicles->firstWhere('plate', 'B 6666 SPD');
        
        $this->assertNotNull($processedVehicle);
        // Expect HIGH threat because it's a Sport Bike with avg rating 2.5 (< 3.0 threshold)
        $this->assertEquals('HIGH', $processedVehicle->threatLevel);
    }
}
