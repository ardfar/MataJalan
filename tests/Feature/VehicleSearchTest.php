<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_vehicles_with_normalized_input()
    {
        // Create a target vehicle
        $targetVehicle = Vehicle::factory()->create([
            'plate_number' => 'A 343 CBF'
        ]);
        
        // Create a non-matching vehicle
        Vehicle::factory()->create([
            'plate_number' => 'B 9999 XXX'
        ]);
        
        // Ensure normalized column is populated (handled by model event)
        $this->assertEquals('A343CBF', $targetVehicle->normalized_plate_number);

        // Test case 1: Search with no spaces
        $response = $this->getJson('/api/vehicles?search=A343CBF');
        
        $response->assertStatus(200)
            ->assertJsonFragment(['plate_number' => 'A 343 CBF'])
            ->assertJsonMissing(['plate_number' => 'B 9999 XXX']);
            
        // Test case 2: Search with lowercase
        $response = $this->getJson('/api/vehicles?search=a343cbf');
        
        $response->assertStatus(200)
            ->assertJsonFragment(['plate_number' => 'A 343 CBF'])
            ->assertJsonMissing(['plate_number' => 'B 9999 XXX']);
            
        // Test case 3: Search with different separator
        $response = $this->getJson('/api/vehicles?search=A-343-CBF');
        
        $response->assertStatus(200)
            ->assertJsonFragment(['plate_number' => 'A 343 CBF'])
            ->assertJsonMissing(['plate_number' => 'B 9999 XXX']);

        // Test case 4: Partial search
        $response = $this->getJson('/api/vehicles?search=343');
        
        $response->assertStatus(200)
            ->assertJsonFragment(['plate_number' => 'A 343 CBF'])
            ->assertJsonMissing(['plate_number' => 'B 9999 XXX']);
    }

    public function test_search_returns_original_formatting()
    {
        $vehicle = Vehicle::factory()->create([
            'plate_number' => 'B 1234 XYZ'
        ]);

        $response = $this->getJson('/api/vehicles?search=b1234xyz');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertNotEmpty($data);
        $this->assertEquals('B 1234 XYZ', $data[0]['plate_number']);
    }
}
