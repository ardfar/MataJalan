<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotorcycleSpecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // HONDA
            ['Honda', 'BeAT', 'CBS', 'Scooter', 110, NULL, 9, 9, 'CVT', 'Bensin', 2],
            ['Honda', 'BeAT', 'Deluxe', 'Scooter', 110, NULL, 9, 9, 'CVT', 'Bensin', 2],
            ['Honda', 'Vario', '125 CBS', 'Scooter', 125, NULL, 11, 10, 'CVT', 'Bensin', 2],
            ['Honda', 'Vario', '160 CBS', 'Scooter', 157, NULL, 15, 13, 'CVT', 'Bensin', 2],
            ['Honda', 'PCX', '160 CBS', 'Scooter', 157, NULL, 16, 14, 'CVT', 'Bensin', 2],
            ['Honda', 'ADV', '160 CBS', 'Scooter', 157, NULL, 16, 14, 'CVT', 'Bensin', 2],
            ['Honda', 'Scoopy', 'Prestige', 'Scooter', 110, NULL, 9, 9, 'CVT', 'Bensin', 2],
            ['Honda', 'Genio', 'CBS', 'Scooter', 110, NULL, 9, 9, 'CVT', 'Bensin', 2],
            ['Honda', 'CBR150R', 'ABS', 'Sport', 149, NULL, 17, 14, 'Manual', 'Bensin', 2],
            ['Honda', 'CBR250RR', 'SP QS', 'Sport', 249, NULL, 42, 25, 'Manual', 'Bensin', 2],
            ['Honda', 'CRF150L', 'Standard', 'Off-road', 149, NULL, 12, 12, 'Manual', 'Bensin', 2],
            ['Honda', 'Supra GTR', '150 Sporty', 'Cub', 149, NULL, 16, 14, 'Manual', 'Bensin', 2],
            ['Honda', 'Revo', 'X', 'Cub', 110, NULL, 8, 8, 'Manual', 'Bensin', 2],

            // YAMAHA
            ['Yamaha', 'NMAX', 'Connected ABS', 'Scooter', 155, NULL, 15, 13, 'CVT', 'Bensin', 2],
            ['Yamaha', 'Aerox', '155 Connected ABS', 'Scooter', 155, NULL, 15, 13, 'CVT', 'Bensin', 2],
            ['Yamaha', 'XMAX', 'Connected', 'Scooter', 250, NULL, 22, 24, 'CVT', 'Bensin', 2],
            ['Yamaha', 'Fazzio', 'Lux Connected', 'Scooter', 125, NULL, 8, 10, 'CVT', 'Hybrid', 2],
            ['Yamaha', 'Grand Filano', 'Lux Connected', 'Scooter', 125, NULL, 8, 10, 'CVT', 'Hybrid', 2],
            ['Yamaha', 'Mio M3', '125', 'Scooter', 125, NULL, 9, 9, 'CVT', 'Bensin', 2],
            ['Yamaha', 'R15', 'Connected ABS', 'Sport', 155, NULL, 19, 14, 'Manual', 'Bensin', 2],
            ['Yamaha', 'R25', 'ABS', 'Sport', 250, NULL, 36, 23, 'Manual', 'Bensin', 2],
            ['Yamaha', 'MT-15', 'Standard', 'Sport', 155, NULL, 19, 14, 'Manual', 'Bensin', 2],
            ['Yamaha', 'XSR 155', 'Standard', 'Sport', 155, NULL, 19, 14, 'Manual', 'Bensin', 2],
            ['Yamaha', 'WR155R', 'Standard', 'Off-road', 155, NULL, 16, 14, 'Manual', 'Bensin', 2],

            // SUZUKI
            ['Suzuki', 'Satria F150', 'Special Edition', 'Cub', 147, NULL, 18, 13, 'Manual', 'Bensin', 2],
            ['Suzuki', 'GSX-R150', 'ABS', 'Sport', 147, NULL, 19, 14, 'Manual', 'Bensin', 2],
            ['Suzuki', 'Address', 'FI', 'Scooter', 113, NULL, 9, 8, 'CVT', 'Bensin', 2],
            ['Suzuki', 'Nex II', 'Standard', 'Scooter', 113, NULL, 9, 8, 'CVT', 'Bensin', 2],
            ['Suzuki', 'Burgman Street', '125 EX', 'Scooter', 125, NULL, 8, 10, 'CVT', 'Bensin', 2],
            ['Suzuki', 'V-Strom', '250 SX', 'Adventure', 249, NULL, 26, 22, 'Manual', 'Bensin', 2],

            // KAWASAKI
            ['Kawasaki', 'Ninja ZX-25R', 'ABS SE', 'Sport', 250, NULL, 51, 22, 'Manual', 'Bensin', 2],
            ['Kawasaki', 'Ninja 250', 'ABS SE', 'Sport', 249, NULL, 39, 23, 'Manual', 'Bensin', 2],
            ['Kawasaki', 'KLX 150', 'BF', 'Off-road', 144, NULL, 12, 11, 'Manual', 'Bensin', 2],
            ['Kawasaki', 'KLX 250', 'Standard', 'Off-road', 249, NULL, 22, 21, 'Manual', 'Bensin', 2],
            ['Kawasaki', 'W175', 'SE', 'Sport', 177, NULL, 13, 13, 'Manual', 'Bensin', 2],
            ['Kawasaki', 'Versys-X', '250 Tourer', 'Adventure', 249, NULL, 34, 21, 'Manual', 'Bensin', 2],

            // VESPA
            ['Vespa', 'Sprint', 'S 150 i-get ABS', 'Scooter', 155, NULL, 12, 12, 'CVT', 'Bensin', 2],
            ['Vespa', 'Primavera', 'S 150 i-get ABS', 'Scooter', 155, NULL, 12, 12, 'CVT', 'Bensin', 2],
            ['Vespa', 'LX', '125 i-get', 'Scooter', 124, NULL, 10, 10, 'CVT', 'Bensin', 2],
            ['Vespa', 'GTS', 'Super Sport 150', 'Scooter', 155, NULL, 15, 15, 'CVT', 'Bensin', 2],
            ['Vespa', 'Elettrica', 'Standard', 'EV', NULL, 4.2, 5, 200, 'Single Speed', 'Listrik', 2],

            // TVS
            ['TVS', 'Callisto', '110', 'Scooter', 109, NULL, 7, 8, 'CVT', 'Bensin', 2],
            ['TVS', 'Ntorq', '125 Race XP', 'Scooter', 124, NULL, 10, 10, 'CVT', 'Bensin', 2],
            ['TVS', 'Ronin', 'TD', 'Sport', 225, NULL, 20, 19, 'Manual', 'Bensin', 2],

            // ALVA (EV)
            ['Alva', 'One', 'Standard', 'EV', NULL, 2.7, 5, 46, 'Single Speed', 'Listrik', 2],
            ['Alva', 'Cervo', 'Standard', 'EV', NULL, 3.6, 13, 53, 'Single Speed', 'Listrik', 2],
            
            // UNITED (EV)
            ['United', 'T1800', 'Standard', 'EV', NULL, 2.7, 4, 30, 'Single Speed', 'Listrik', 2],
            ['United', 'TX3000', 'Standard', 'EV', NULL, 5.4, 6, 40, 'Single Speed', 'Listrik', 2],
        ];

        $records = [];
        $now = now();
        foreach ($data as $row) {
            $records[] = [
                'type' => 'motorcycle',
                'brand' => $row[0],
                'model' => $row[1],
                'variant' => $row[2],
                'category' => $row[3],
                'engine_cc' => $row[4],
                'battery_kwh' => $row[5],
                'horsepower' => $row[6],
                'torque' => $row[7],
                'transmission' => $row[8],
                'fuel_type' => $row[9],
                'seat_capacity' => $row[10],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('vehicle_specs')->insert($records);
    }
}
