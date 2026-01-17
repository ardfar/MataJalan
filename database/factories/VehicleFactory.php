<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate realistic Indonesian-like license plates
        // e.g. B 1234 ABC
        $prefix = $this->faker->randomElement(['B', 'D', 'F', 'A', 'H', 'L']);
        $number = $this->faker->numberBetween(100, 9999);
        $suffix = $this->faker->regexify('[A-Z]{2,3}');
        $plate = "$prefix $number $suffix";

        $models = [
            'Toyota Fortuner - Black', 'Mitsubishi Pajero - Black', 'Honda Civic - White', 
            'Toyota Avanza - Silver', 'Honda Jazz - Red', 'Suzuki Ertiga - Grey', 
            'Toyota Innova - Black', 'Hyundai Creta - White', 'Wuling AirEV - Blue'
        ];

        return [
            'plate_number' => $plate,
            'model' => $this->faker->randomElement($models),
        ];
    }
}
