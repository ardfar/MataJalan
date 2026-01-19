<?php

namespace Tests\Feature;

use App\Models\Rating;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_rating_defaults_to_pending()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('vehicle.storeRating', $vehicle->uuid), [
                'rating' => 5,
                'comment' => 'Great car',
                'tags' => 'safe, clean',
                'honesty_declaration' => 1,
            ]);

        if ($response->exception) {
            dump($response->exception->getMessage());
        }
        
        $response->assertRedirect(); // Should redirect on success

        $rating = Rating::first();
        $this->assertNotNull($rating, 'Rating was not created');
        $this->assertEquals('pending', $rating->status);
    }

    public function test_only_admin_can_approve_rating()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $rating = Rating::factory()->create(['status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.ratings.approve', $rating));

        $this->assertEquals('approved', $rating->fresh()->status);
        $this->assertEquals($admin->id, $rating->fresh()->approved_by);
    }

    public function test_user_cannot_approve_rating()
    {
        $user = User::factory()->create(['role' => 'user']);
        $rating = Rating::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($user)
            ->patch(route('admin.ratings.approve', $rating));

        $response->assertForbidden();
        $this->assertEquals('pending', $rating->fresh()->status);
    }

    public function test_public_view_shows_only_approved_ratings()
    {
        $vehicle = Vehicle::factory()->create();
        $approved = Rating::factory()->create(['vehicle_id' => $vehicle->id, 'status' => 'approved', 'comment' => 'Visible']);
        $pending = Rating::factory()->create(['vehicle_id' => $vehicle->id, 'status' => 'pending', 'comment' => 'Hidden']);

        $response = $this->get(route('vehicle.show', $vehicle->uuid));

        $response->assertSee('Visible');
        $response->assertDontSee('Hidden');
    }
}
