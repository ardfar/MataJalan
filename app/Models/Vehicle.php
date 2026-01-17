<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['plate_number', 'model'];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
