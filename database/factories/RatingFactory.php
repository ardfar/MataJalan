<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tags = [
            'Aggressive', 'Speeding', 'Safe Driver', 'Polite', 
            'Tailgating', 'Running Red Light', 'Illegal Parking', 
            'Blocking Road', 'Using Strobe Lights'
        ];

        return [
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph(),
            'tags' => $this->faker->randomElements($tags, rand(0, 3)),
        ];
    }
}
