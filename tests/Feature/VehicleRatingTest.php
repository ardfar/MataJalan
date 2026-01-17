<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class VehicleRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_rating_and_view_vehicle()
    {
        // 1. Add Rating
        $response = $this->postJson('/api/ratings', [
            'plate_number' => 'B 1234 XYZ',
            'rating' => 5,
            'comment' => 'Great driver!',
            'tags' => ['polite', 'safe']
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('vehicles', ['plate_number' => 'B 1234 XYZ']);
        $this->assertDatabaseHas('ratings', ['rating' => 5, 'comment' => 'Great driver!']);

        // 2. View Vehicle
        $response = $this->getJson('/api/vehicles/B 1234 XYZ');

        $response->assertStatus(200)
            ->assertJson([
                'plate_number' => 'B 1234 XYZ',
                'ratings_count' => 1,
                'ratings_avg_rating' => 5
            ]);
    }
}
