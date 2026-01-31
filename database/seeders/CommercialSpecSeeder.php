<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommercialSpecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // TRUCKS
            // Brand, Model, Variant, Category, CC, HP, Torque, Trans, Fuel, Seats, Cargo(kg), GVWR(kg), Axles
            ['Mitsubishi Fuso', 'Canter', 'FE 74 HD', 'Light Duty Truck', 3907, 136, 420, 'Manual', 'Diesel', 3, 5000, 7500, 2],
            ['Mitsubishi Fuso', 'Fighter X', 'FN 62 F', 'Heavy Duty Truck', 7545, 270, 880, 'Manual', 'Diesel', 3, 16000, 26000, 3],
            ['Hino', '300 Series', 'Dutro 130 HD', 'Light Duty Truck', 4009, 130, 380, 'Manual', 'Diesel', 3, 5500, 8250, 2],
            ['Hino', '500 Series', 'Ranger FL 260 JW', 'Heavy Duty Truck', 7684, 260, 794, 'Manual', 'Diesel', 3, 15000, 26000, 3],
            ['Isuzu', 'Elf', 'NMR 71', 'Light Duty Truck', 4570, 125, 350, 'Manual', 'Diesel', 3, 5000, 8250, 2],
            ['Isuzu', 'Giga', 'FVZ 34', 'Heavy Duty Truck', 7790, 285, 900, 'Manual', 'Diesel', 3, 18000, 26000, 3],
            ['UD Trucks', 'Quester', 'GWE 410', 'Tractor Head', 10800, 410, 1990, 'Manual', 'Diesel', 2, 40000, 50000, 3],
            ['Mercedes-Benz', 'Axor', '2528 C', 'Heavy Duty Truck', 6374, 280, 1100, 'Manual', 'Diesel', 2, 15000, 26500, 3],
            ['Scania', 'P360', 'B6x2', 'Heavy Duty Truck', 13000, 360, 1750, 'Automatic', 'Diesel', 2, 20000, 30000, 3],
            
            // BUSES
            // Brand, Model, Variant, Category, CC, HP, Torque, Trans, Fuel, Seats, Cargo(kg), GVWR(kg), Axles
            ['Mercedes-Benz', 'OH 1626', 'L', 'Intercity Coach', 6374, 260, 950, 'Automatic', 'Diesel', 45, 2000, 16000, 2],
            ['Mercedes-Benz', 'OC 500 RF', '2542', 'Intercity Coach', 11967, 422, 1900, 'Automatic', 'Diesel', 50, 3000, 25000, 3],
            ['Hino', 'R 260', 'RK8', 'Intercity Coach', 7684, 260, 760, 'Manual', 'Diesel', 45, 2000, 14200, 2],
            ['Hino', 'RM 280', 'Euro 4', 'Intercity Coach', 7684, 280, 840, 'Manual', 'Diesel', 45, 2000, 16000, 2],
            ['Scania', 'K410IB', '6x2', 'Intercity Coach', 13000, 410, 2000, 'Automatic', 'Diesel', 54, 3000, 25000, 3],
            ['Mitsubishi Fuso', 'Canter Bus', 'FE 71', 'Micro Bus', 3907, 108, 275, 'Manual', 'Diesel', 15, 500, 5000, 2],
            ['Isuzu', 'Elf Microbus', 'NLR 55', 'Micro Bus', 2771, 100, 220, 'Manual', 'Diesel', 16, 500, 4500, 2],
        ];

        $records = [];
        $now = now();
        foreach ($data as $row) {
            $records[] = [
                'type' => str_contains($row[3], 'Truck') || str_contains($row[3], 'Tractor') || str_contains($row[3], 'Pickup') ? 'truck' : 'bus',
                'brand' => $row[0],
                'model' => $row[1],
                'variant' => $row[2],
                'category' => $row[3],
                'engine_cc' => $row[4],
                'battery_kwh' => NULL,
                'horsepower' => $row[5],
                'torque' => $row[6],
                'transmission' => $row[7],
                'fuel_type' => $row[8],
                'seat_capacity' => $row[9],
                'cargo_capacity_kg' => $row[10],
                'gvwr_kg' => $row[11],
                'axle_count' => $row[12],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('vehicle_specs')->insert($records);
    }
}
