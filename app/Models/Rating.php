<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'vehicle_id', 
        'rating', 
        'comment', 
        'tags',
        'latitude',
        'longitude',
        'address',
        'is_honest'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_honest' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function media()
    {
        return $this->hasMany(RatingMedia::class);
    }
}
