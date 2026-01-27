<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleSpec extends Model
{
    const AVAILABLE_BRANDS = [
        'Toyota', 'Honda', 'Daihatsu', 'Mitsubishi', 'Suzuki', 'Hyundai', 
        'Wuling', 'Nissan', 'Mazda', 'Kia', 'BMW', 'Mercedes-Benz', 
        'Lexus', 'Isuzu', 'Chery', 'DFSK'
    ];

    protected $fillable = [
        'brand',
        'model',
        'variant',
        'category',
        'engine_cc',
        'battery_kwh',
        'horsepower',
        'torque',
        'transmission',
        'fuel_type',
        'seat_capacity',
    ];
}
