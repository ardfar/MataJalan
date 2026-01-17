<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationFeedback extends Model
{
    use HasFactory;

    protected $table = 'registration_feedbacks';

    protected $fillable = ['vehicle_id', 'user_id', 'rating', 'comment'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
