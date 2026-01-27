<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleSpec extends Model
{
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
