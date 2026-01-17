<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function index()
    {
        // 1. Fetch all vehicles with ratings data
        $allVehicles = Vehicle::withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->take(100) // Limit for performance in demo
            ->get();

        // 2. Process data for View (calculate threat, score, etc.)
        $processedVehicles = $allVehicles->map(function ($vehicle) {
            $avgRating = $vehicle->ratings_avg_rating ? floatval($vehicle->ratings_avg_rating) : 0;
            $score = round($avgRating * 20); // 0-100
            
            // Threat Logic
            $threatLevel = 'LOW';
            if ($vehicle->ratings_count > 0) {
                if ($avgRating < 2.5) $threatLevel = 'HIGH';
                elseif ($avgRating < 4.0) $threatLevel = 'MEDIUM';
            }

            // Mock Data
            $location = $vehicle->location ?? ['Jl. Jend. Sudirman', 'Tol Dalam Kota', 'Simpang Lima'][rand(0,2)];
            $status = $vehicle->status ?? 'ACTIVE';

            return (object) [
                'id' => $vehicle->id,
                'plate' => $vehicle->plate_number,
                'model' => $vehicle->model ?? 'Unknown Model',
                'score' => $score,
                'threatLevel' => $threatLevel,
                'lastSeen' => $vehicle->updated_at->diffForHumans(),
                'location' => $location,
                'reports' => $vehicle->ratings_count,
                'tags' => $vehicle->ratings->pluck('tags')->flatten()->unique()->values()->all() ?? [],
                'status' => $status,
                'raw_updated_at' => $vehicle->updated_at
            ];
        });

        // 3. Filter for Sections
        $highThreatVehicles = $processedVehicles->where('threatLevel', 'HIGH')->take(5);
        
        // 4. Analytics Data
        // A. Threat Distribution
        $threatStats = [
            'HIGH' => $processedVehicles->where('threatLevel', 'HIGH')->count(),
            'MEDIUM' => $processedVehicles->where('threatLevel', 'MEDIUM')->count(),
            'LOW' => $processedVehicles->where('threatLevel', 'LOW')->count(),
        ];

        // B. Top Tags (Basic frequency count)
        $allTags = $processedVehicles->pluck('tags')->flatten();
        $tagCounts = $allTags->countBy()->sortDesc()->take(5);

        // C. Activity (Mocked or based on updated_at)
        // Group by hour of day for the last 24h simulation
        $activityData = $processedVehicles->groupBy(function($v) {
            return $v->raw_updated_at->format('H:00');
        })->map->count()->take(10); // simplified

        return view('home', [
            'vehicles' => $processedVehicles->values(), // For the main grid
            'highThreatVehicles' => $highThreatVehicles,
            'analytics' => [
                'threats' => $threatStats,
                'tags' => $tagCounts,
                'activity' => $activityData
            ]
        ]);
    }

    public function search(Request $request)
    {
        $request->validate(['plate_number' => 'required|string']);
        
        $normalizedInput = Vehicle::normalizePlate($request->plate_number);
        
        // Try to find existing vehicle by normalized plate to get the original formatting
        $vehicle = Vehicle::where('normalized_plate_number', $normalizedInput)->first();
        
        if ($vehicle) {
            // If found, redirect to the canonical URL with original formatting
            return redirect()->route('vehicle.show', ['plate_number' => $vehicle->plate_number]);
        }
        
        // If not found, redirect to standardized/normalized version
        return redirect()->route('vehicle.show', ['plate_number' => $normalizedInput]);
    }

    public function show($plate_number)
    {
        // 1. Try exact match first
        $vehicle = Vehicle::where('plate_number', $plate_number)
            ->with(['ratings.user' => function($query) {
                $query->select('id', 'name');
            }])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->first();

        // 2. If not found, try fuzzy match using normalized column
        if (!$vehicle) {
            $normalizedInput = Vehicle::normalizePlate($plate_number);
            $vehicle = Vehicle::where('normalized_plate_number', $normalizedInput)
                ->with(['ratings.user' => function($query) {
                    $query->select('id', 'name');
                }])
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->first();
                
            // Optional: If found via fuzzy match but URL is different, we could redirect to canonical URL.
            // But for now, showing the result is enough and less confusing than multiple redirects if not handled carefully.
        }

        return view('vehicle.show', compact('vehicle', 'plate_number'));
    }

    public function rate($plate_number)
    {
        return view('vehicle.rate', compact('plate_number'));
    }

    public function storeRating(Request $request, $plate_number)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'tags' => 'nullable|string' // comma separated in form
        ]);

        $vehicle = Vehicle::firstOrCreate(
            ['plate_number' => $plate_number]
        );

        // Handle tags: "safe, fast" -> ["safe", "fast"]
        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : null;

        // Auto-login or create user if not exists (demo mode)
        // In real app, middleware would handle this. 
        // For this demo, let's use ID 1 or current user.
        $userId = Auth::id() ?? 1;
         if ($userId === 1 && !User::find(1)) {
            User::factory()->create(['id' => 1, 'name' => 'Anonymous Driver', 'email' => 'anon@ivip.test']);
        }

        Rating::create([
            'user_id' => $userId,
            'vehicle_id' => $vehicle->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'tags' => $tags,
        ]);

        return redirect()->route('vehicle.show', ['plate_number' => $plate_number])
            ->with('success', 'Rating submitted successfully!');
    }
}
