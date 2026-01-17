<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingMedia extends Model
{
    use HasFactory;

    protected $fillable = ['rating_id', 'file_path', 'file_type', 'caption'];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
