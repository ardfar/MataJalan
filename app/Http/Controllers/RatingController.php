<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'tags' => 'nullable|array'
        ]);

        $vehicle = Vehicle::firstOrCreate(
            ['plate_number' => $request->plate_number]
        );

        // For now, if no user is authenticated, we might need to create a dummy user or fail.
        // Assuming API will be consumed by authenticated users.
        // If testing without auth, ensure a user exists and maybe hardcode ID 1 for dev.
        $userId = $request->user() ? $request->user()->id : 1;
        
        // Ensure user 1 exists if we fallback to it
        if ($userId === 1 && !\App\Models\User::find(1)) {
            \App\Models\User::factory()->create(['id' => 1, 'name' => 'Test User', 'email' => 'test@example.com']);
        }

        $rating = Rating::create([
            'user_id' => $userId,
            'vehicle_id' => $vehicle->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'tags' => $request->tags,
        ]);

        return response()->json($rating, 201);
    }
}
