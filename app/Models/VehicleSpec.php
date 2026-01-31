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

    const AVAILABLE_COMMERCIAL_BRANDS = [
        'Hino', 'Mitsubishi Fuso', 'Isuzu', 'UD Trucks', 'Mercedes-Benz', 
        'Scania', 'Volvo', 'FAW', 'Tata', 'MAN'
    ];

    const CAR_CATEGORIES = [
        'MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial'
    ];

    const MOTORCYCLE_CATEGORIES = [
        'Scooter', 'Sport', 'Cub', 'Cruiser', 'Off-road', 'Adventure', 'Touring', 'EV', 'Street'
    ];

    const TRUCK_CATEGORIES = [
        'Light Duty Truck', 'Medium Duty Truck', 'Heavy Duty Truck', 'Pickup', 'Tractor Head'
    ];

    const BUS_CATEGORIES = [
        'City Transit', 'Intercity Coach', 'School Bus', 'Mini Bus', 'Micro Bus'
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
        'cargo_capacity_kg',
        'gvwr_kg',
        'axle_count',
    ];

    public static function getAvailableBrands($type = null)
    {
        if ($type === 'car') {
            return self::AVAILABLE_CAR_BRANDS;
        } elseif ($type === 'motorcycle') {
            return self::AVAILABLE_MOTORCYCLE_BRANDS;
        } elseif ($type === 'truck' || $type === 'bus') {
            return self::AVAILABLE_COMMERCIAL_BRANDS;
        }
        
        return array_unique(array_merge(
            self::AVAILABLE_CAR_BRANDS, 
            self::AVAILABLE_MOTORCYCLE_BRANDS,
            self::AVAILABLE_COMMERCIAL_BRANDS
        ));
    }

    public static function getCategories($type = null)
    {
        if ($type === 'car') {
            return self::CAR_CATEGORIES;
        } elseif ($type === 'motorcycle') {
            return self::MOTORCYCLE_CATEGORIES;
        } elseif ($type === 'truck') {
            return self::TRUCK_CATEGORIES;
        } elseif ($type === 'bus') {
            return self::BUS_CATEGORIES;
        }

        return array_unique(array_merge(
            self::CAR_CATEGORIES, 
            self::MOTORCYCLE_CATEGORIES,
            self::TRUCK_CATEGORIES,
            self::BUS_CATEGORIES
        ));
    }
}
