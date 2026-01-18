<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\RatingMedia;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\RegistrationFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                'uuid' => $vehicle->uuid,
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
            // If found, redirect to the canonical URL with UUID
            return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid]);
        }
        
        // If not found, redirect to standardized/normalized version
        return redirect()->route('vehicle.show', ['identifier' => $normalizedInput]);
    }

    public function show($identifier)
    {
        $plate_number = $identifier;
        $vehicle = null;

        if (Str::isUuid($identifier)) {
            // Use UUID to find
            $vehicle = Vehicle::where('uuid', $identifier)
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->firstOrFail();
            $plate_number = $vehicle->plate_number;
        } else {
            // Backward compatibility: Try exact match first
            $vehicle = Vehicle::where('plate_number', $identifier)
                ->withAvg('ratings', 'rating')
                ->withCount('ratings')
                ->first();

            // If not found, try fuzzy match using normalized column
            if (!$vehicle) {
                $normalizedInput = Vehicle::normalizePlate($identifier);
                $vehicle = Vehicle::where('normalized_plate_number', $normalizedInput)
                    ->withAvg('ratings', 'rating')
                    ->withCount('ratings')
                    ->first();
                $plate_number = $normalizedInput ?: $identifier;
            }

            // If vehicle found by plate, redirect to canonical UUID URL
            if ($vehicle) {
                return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid]);
            }
        }

        $ratings = null;
        if ($vehicle) {
            $ratings = $vehicle->ratings()
                ->with(['user' => function($query) {
                    $query->select('id', 'name');
                }, 'media'])
                ->latest()
                ->paginate(5);
        }

        return view('vehicle.show', compact('vehicle', 'plate_number', 'ratings'));
    }

    public function rate($identifier)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit a report.');
        }

        if (Str::isUuid($identifier)) {
             $vehicle = Vehicle::where('uuid', $identifier)->firstOrFail();
             $plate_number = $vehicle->plate_number;
        } else {
             $vehicle = Vehicle::where('plate_number', $identifier)->orWhere('normalized_plate_number', Vehicle::normalizePlate($identifier))->first();
             if ($vehicle) {
                 return redirect()->route('vehicle.rate', ['identifier' => $vehicle->uuid]);
             }
             $plate_number = $identifier;
        }

        return view('vehicle.rate', compact('plate_number'));
    }

    public function storeRating(Request $request, $identifier)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit a report.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'tags' => 'required|string', // Changed to required as per requirement "Minimum 1 tag"
            'honesty_declaration' => 'accepted',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240', // 10MB
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
        ]);

        $vehicle = null;
        $plate_number = $identifier;

        if (Str::isUuid($identifier)) {
            $vehicle = Vehicle::where('uuid', $identifier)->firstOrFail();
            $plate_number = $vehicle->plate_number;
        } else {
            // Find or create based on plate number
            $vehicle = Vehicle::firstOrCreate(
                ['plate_number' => $identifier]
            );
            // Since firstOrCreate might create a new one, we let it handle UUID generation via model boot/trait
        }

        // Handle tags: "safe, fast" -> ["safe", "fast"]
        $tags = $request->tags ? array_filter(array_map('trim', explode(',', $request->tags))) : [];

        $rating = Rating::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'tags' => $tags,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'is_honest' => true,
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $path = $file->store('rating-media', 'public');
                $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
                
                // Get caption for this specific file if available
                $caption = $request->input('media_captions')[$index] ?? null;

                RatingMedia::create([
                    'rating_id' => $rating->id,
                    'file_path' => $path,
                    'file_type' => $type,
                    'caption' => $caption,
                ]);
            }
        }

        return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid])
            ->with('success', 'Rating submitted successfully!');
    }

    public function create($identifier)
    {
        $plate_number = $identifier;
        if (Str::isUuid($identifier)) {
             $vehicle = Vehicle::where('uuid', $identifier)->first();
             if ($vehicle) {
                 return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid]);
             }
             // If UUID not found, invalid request really, but maybe allow create? 
             // Hard to create a vehicle from just a UUID. Assume identifier is plate.
        } else {
             // Check if vehicle exists by plate
             $vehicle = Vehicle::where('plate_number', $identifier)->orWhere('normalized_plate_number', Vehicle::normalizePlate($identifier))->first();
             if ($vehicle) {
                 return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid]);
             }
        }

        return view('vehicle.create', compact('plate_number'));
    }

    public function store(Request $request, $identifier)
    {
        $request->validate([
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'color' => 'required|string',
            'vin' => 'required|string|unique:vehicles,vin',
        ]);

        // $identifier is plate_number
        $vehicle = Vehicle::create([
            'plate_number' => $identifier,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'vin' => $request->vin,
        ]);

        return redirect()->route('vehicle.registered', ['vehicle' => $vehicle->uuid]);
    }

    public function registered(Vehicle $vehicle)
    {
        return view('vehicle.registered', compact('vehicle'));
    }

    public function storeFeedback(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        RegistrationFeedback::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => Auth::id() ?? 1, // Fallback for demo
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid])
            ->with('success', 'Vehicle registered and feedback submitted!');
    }
}
