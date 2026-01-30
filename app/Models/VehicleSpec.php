<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleSpec extends Model
{
    const AVAILABLE_CAR_BRANDS = [
        'Toyota', 'Honda', 'Daihatsu', 'Mitsubishi', 'Suzuki', 'Hyundai', 
        'Wuling', 'Nissan', 'Mazda', 'Kia', 'BMW', 'Mercedes-Benz', 
        'Lexus', 'Isuzu', 'Chery', 'DFSK'
    ];

    const AVAILABLE_MOTORCYCLE_BRANDS = [
        'Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'Vespa', 'TVS', 'Kymco',
        'Ducati', 'BMW Motorrad', 'Harley-Davidson', 'Royal Enfield', 'KTM'
    ];

    const CAR_CATEGORIES = [
        'MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial'
    ];

    const MOTORCYCLE_CATEGORIES = [
        'Scooter', 'Sport', 'Cub', 'Cruiser', 'Off-road', 'Adventure', 'Touring', 'EV', 'Street'
    ];

    protected $fillable = [
        'type',
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

    public static function getAvailableBrands($type = null)
    {
        if ($type === 'car') {
            return self::AVAILABLE_CAR_BRANDS;
        } elseif ($type === 'motorcycle') {
            return self::AVAILABLE_MOTORCYCLE_BRANDS;
        }
        
        return array_unique(array_merge(self::AVAILABLE_CAR_BRANDS, self::AVAILABLE_MOTORCYCLE_BRANDS));
    }

    public static function getCategories($type = null)
    {
        if ($type === 'car') {
            return self::CAR_CATEGORIES;
        } elseif ($type === 'motorcycle') {
            return self::MOTORCYCLE_CATEGORIES;
        }

        return array_unique(array_merge(self::CAR_CATEGORIES, self::MOTORCYCLE_CATEGORIES));
    }
}
