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

        $brands = ['Toyota', 'Honda', 'Mitsubishi', 'Suzuki', 'Hyundai', 'Wuling'];
        $models = [
            'Toyota' => ['Fortuner', 'Avanza', 'Innova', 'Camry'],
            'Honda' => ['Civic', 'Jazz', 'CR-V', 'Brio'],
            'Mitsubishi' => ['Pajero Sport', 'Xpander'],
            'Suzuki' => ['Ertiga', 'Jimny'],
            'Hyundai' => ['Creta', 'Stargazer', 'Ioniq 5'],
            'Wuling' => ['AirEV', 'Almaz']
        ];
        
        $brand = $this->faker->randomElement($brands);
        $model = $this->faker->randomElement($models[$brand]);
        $colors = ['Black', 'White', 'Silver', 'Grey', 'Red', 'Blue'];

        return [
            'plate_number' => $plate,
            'make' => $brand,
            'model' => $model,
            'year' => $this->faker->numberBetween(2015, 2025),
            'color' => $this->faker->randomElement($colors),
            'vin' => strtoupper($this->faker->bothify('???#############')),
        ];
    }
}
