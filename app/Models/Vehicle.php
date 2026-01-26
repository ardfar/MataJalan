<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Vehicle extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'plate_number', 
        'normalized_plate_number',
        'make',
        'model',
        'year',
        'color',
        'vin',
        'owned_by_user_id',
    ];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return ['uuid'];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owned_by_user_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function registrationFeedbacks()
    {
        return $this->hasMany(RegistrationFeedback::class);
    }

    public function vehicleUsers()
    {
        return $this->hasMany(VehicleUser::class);
    }
}
