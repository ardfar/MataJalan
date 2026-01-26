<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyVehicleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_my_vehicles_list()
    {
        $response = $this->actingAs($this->user)->get(route('my-vehicles.index'));

        $response->assertStatus(200);
        $response->assertSee('REGISTERED_FLEET');
        $response->assertSee('ADD_MY_VEHICLE');
    }

    /** @test */
    public function user_can_view_search_page()
    {
        $response = $this->actingAs($this->user)->get(route('my-vehicles.create'));

        $response->assertStatus(200);
        $response->assertSee('VEHICLE_IDENTIFICATION');
        $response->assertSee('SEARCH_VEHICLE');
    }

    /** @test */
    public function check_redirects_to_add_driver_info_if_vehicle_exists()
    {
        $vehicle = Vehicle::factory()->create(['plate_number' => 'B 1234 TES']);

        $response = $this->actingAs($this->user)->post(route('my-vehicles.check'), [
            'plate_number' => 'B 1234 TES',
        ]);

        $response->assertRedirect(route('vehicle.user.create', $vehicle->uuid));
    }

    /** @test */
    public function check_redirects_to_register_if_vehicle_not_exists()
    {
        $response = $this->actingAs($this->user)->post(route('my-vehicles.check'), [
            'plate_number' => 'B 9999 NEW',
        ]);

        $response->assertRedirect(route('my-vehicles.register', ['plate' => 'B 9999 NEW']));
    }

    /** @test */
    public function register_page_shows_form()
    {
        $response = $this->actingAs($this->user)->get(route('my-vehicles.register', ['plate' => 'B 9999 NEW']));

        $response->assertStatus(200);
        $response->assertSee('B 9999 NEW');
        $response->assertSee('VEHICLE_NOT_FOUND');
    }

    /** @test */
    public function user_can_register_new_vehicle_and_auto_adds_to_fleet()
    {
        $response = $this->actingAs($this->user)->post(route('my-vehicles.store'), [
            'plate_number' => 'B 9999 NEW',
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2022,
            'color' => 'Black',
            'vin' => 'MHF1234567890',
        ]);

        $vehicle = Vehicle::where('plate_number', 'B 9999 NEW')->first();
        $this->assertNotNull($vehicle);
        
        // Check ownership
        $this->assertEquals($this->user->id, $vehicle->owned_by_user_id);

        // Check auto-generated vehicle user record
        $this->assertDatabaseHas('vehicle_users', [
            'registered_by' => $this->user->id,
            'vehicle_id' => $vehicle->id,
            'role_type' => 'personal',
            'driver_name' => $this->user->name,
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('my-vehicles.index'));
    }

    /** @test */
    public function user_can_register_new_vehicle_without_vin()
    {
        $response = $this->actingAs($this->user)->post(route('my-vehicles.store'), [
            'plate_number' => 'B 8888 NOVIN',
            'make' => 'Toyota',
            'model' => 'Avanza',
            'year' => 2021,
            'color' => 'White',
            // vin is missing
        ]);

        $vehicle = Vehicle::where('plate_number', 'B 8888 NOVIN')->first();
        $this->assertNotNull($vehicle);
        $this->assertNull($vehicle->vin);
        
        $response->assertRedirect(route('my-vehicles.index'));
    }
}
