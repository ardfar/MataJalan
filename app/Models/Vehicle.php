<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['plate_number', 'model', 'normalized_plate_number'];

    protected static function booted()
    {
        static::saving(function ($vehicle) {
            $vehicle->normalized_plate_number = static::normalizePlate($vehicle->plate_number);
        });
    }

    public static function normalizePlate($plate)
    {
        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $plate));
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
