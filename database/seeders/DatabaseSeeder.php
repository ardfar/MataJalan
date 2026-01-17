<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rating;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
            'kyc_status' => 'approved',
            'kyc_verified_at' => now(),
        ]);

        // Regular User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create 10 Users
        $users = User::factory(10)->create();

        // Create 50 Vehicles with Ratings
        Vehicle::factory(50)->create()->each(function ($vehicle) use ($users) {
            // 0 to 5 ratings per vehicle
            $ratingCount = rand(0, 5);
            
            for ($i = 0; $i < $ratingCount; $i++) {
                Rating::factory()->create([
                    'vehicle_id' => $vehicle->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        });
    }
}
