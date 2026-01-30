<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\RatingMedia;
use App\Models\Vehicle;
use App\Models\VehicleSpec;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\RegistrationFeedback;
use App\Notifications\NewRatingPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebController extends Controller
{
    public function index()
    {
        // 1. Fetch all vehicles with APPROVED ratings data
        $allVehicles = Vehicle::withAvg(['ratings' => function($query) {
                $query->approved();
            }], 'rating')
            ->withCount(['ratings' => function($query) {
                $query->approved();
            }])
            ->latest()
            ->take(100) // Limit for performance in demo
            ->get();

        // Prefetch specs to avoid N+1
        $specs = VehicleSpec::all()->groupBy(function($item) {
            return strtolower($item->brand . '|' . $item->model);
        });

        // 2. Process data for View (calculate threat, score, etc.)
        $processedVehicles = $allVehicles->map(function ($vehicle) use ($specs) {
            $avgRating = $vehicle->ratings_avg_rating ? floatval($vehicle->ratings_avg_rating) : 0;
            
            // Find spec
            $specKey = strtolower($vehicle->make . '|' . $vehicle->model);
            $spec = $specs->get($specKey)?->first();

            $score = $this->calculateScore($avgRating, $vehicle, $spec);
            $threatLevel = $this->calculateThreatLevel($avgRating, $vehicle->ratings_count, $vehicle, $spec);

            // Mock Data
            $location = $vehicle->location ?? ['Jl. Jend. Sudirman', 'Tol Dalam Kota', 'Simpang Lima'][rand(0,2)];
            $status = $vehicle->status ?? 'ACTIVE';

            return (object) [
                'id' => $vehicle->id,
                'uuid' => $vehicle->uuid,
                'plate' => $vehicle->plate_number,
                'model' => $vehicle->model ?? 'Unknown Model',
                'type' => $vehicle->type ?? 'car',
                'score' => $score,
                'threatLevel' => $threatLevel,
                'lastSeen' => $vehicle->updated_at->diffForHumans(),
                'location' => $location,
                'reports' => $vehicle->ratings_count,
                // Only showing tags from approved ratings would be expensive here without optimized query
                // For demo, we keep this simple or fetch relationship if needed. 
                // Let's assume we want only approved tags.
                'tags' => $vehicle->ratings()->approved()->pluck('tags')->flatten()->unique()->values()->all() ?? [],
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

    private function calculateScore($avgRating, $vehicle, $spec = null)
    {
        $baseScore = round($avgRating * 20); // 0-100

        // Adjust score based on vehicle characteristics (Requirement 5)
        if ($vehicle->type === 'motorcycle' && $spec) {
            // Lower score slightly for high performance bikes if rating is low (amplifying bad behavior)
            if ($avgRating < 3.0) {
                if ($spec->engine_cc > 250 || $spec->category === 'Sport') {
                    $baseScore = max(0, $baseScore - 10);
                }
            }
        }

        return $baseScore;
    }

    private function calculateThreatLevel($avgRating, $ratingCount, $vehicle, $spec = null)
    {
        if ($ratingCount === 0) return 'LOW';

        $threatLevel = 'LOW';
        
        // Base Thresholds
        $highThreshold = 2.5;
        $mediumThreshold = 4.0;

        // Dynamic Thresholds for Motorcycles (Requirement 5)
        if ($vehicle->type === 'motorcycle' && $spec) {
            // High performance bikes are considered higher threat if rating is low
            if ($spec->engine_cc > 250 || $spec->category === 'Sport') {
                $highThreshold = 3.0; // Stricter: < 3.0 is HIGH
                $mediumThreshold = 4.5; // Stricter: < 4.5 is MEDIUM
            }
        }

        if ($avgRating < $highThreshold) $threatLevel = 'HIGH';
        elseif ($avgRating < $mediumThreshold) $threatLevel = 'MEDIUM';

        return $threatLevel;
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
                ->withAvg(['ratings' => function($q) { $q->approved(); }], 'rating')
                ->withCount(['ratings' => function($q) { $q->approved(); }])
                ->firstOrFail();
            $plate_number = $vehicle->plate_number;
        } else {
            // Backward compatibility: Try exact match first
            $vehicle = Vehicle::where('plate_number', $identifier)
                ->withAvg(['ratings' => function($q) { $q->approved(); }], 'rating')
                ->withCount(['ratings' => function($q) { $q->approved(); }])
                ->first();

            // If not found, try fuzzy match using normalized column
            if (!$vehicle) {
                $normalizedInput = Vehicle::normalizePlate($identifier);
                $vehicle = Vehicle::where('normalized_plate_number', $normalizedInput)
                    ->withAvg(['ratings' => function($q) { $q->approved(); }], 'rating')
                    ->withCount(['ratings' => function($q) { $q->approved(); }])
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
            // Show approved ratings AND user's own pending ratings
            $ratingsQuery = $vehicle->ratings()
                ->where(function($query) {
                    $query->approved();
                    if (Auth::check()) {
                        $query->orWhere('user_id', Auth::id());
                    }
                });
            
            $ratings = $ratingsQuery->with(['user' => function($query) {
                    $query->select('id', 'name');
                }, 'media'])
                ->latest()
                ->paginate(5);
            
            // Explicitly load ratings for the view to use in the map, filtered by the same logic but without pagination
            $vehicle->setRelation('ratings', $ratingsQuery->get());
        }

        // Access Control Logic for Specifications
        $canViewSpecs = false;
        $specs = null;
        if ($vehicle && Auth::check()) {
            $user = Auth::user();
            // Check allowed roles: Superadmin, Admin, Tier 1 (Verified)
            if ($user->isSuperAdmin() || $user->isAdmin() || ($user->hasRole(User::ROLE_TIER_1) && $user->kyc_verified_at)) {
                $canViewSpecs = true;
                
                // Fetch Vehicle Specs if make/model matches
                if ($vehicle->make && $vehicle->model) {
                    $specs = VehicleSpec::where('brand', $vehicle->make)
                        ->where('model', $vehicle->model)
                        ->get(); // Get all variants
                }

                // Audit Log
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'VIEW_SPECS_DRIVERS',
                    'description' => "Viewed specifications and driver info for vehicle {$vehicle->plate_number}",
                    'ip_address' => request()->ip(),
                ]);
            }
        }

        return view('vehicle.show', compact('vehicle', 'plate_number', 'ratings', 'canViewSpecs', 'specs'));
    }

    public function tutorial()
    {
        return view('tutorial');
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
            'status' => 'pending', // Default status
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
        
        // Notification logic
        \Illuminate\Support\Facades\Notification::route('mail', 'admin@matajalan.os')->notify(new NewRatingPending($rating));

        return redirect()->route('vehicle.show', ['identifier' => $vehicle->uuid])
            ->with('success', 'Rating submitted and awaiting verification!');
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

        $carBrands = VehicleSpec::AVAILABLE_CAR_BRANDS;
        $motorcycleBrands = VehicleSpec::AVAILABLE_MOTORCYCLE_BRANDS;

        return view('vehicle.create', compact('plate_number', 'carBrands', 'motorcycleBrands'));
    }

    public function store(Request $request, $identifier)
    {
        $request->validate([
            'type' => 'required|in:car,motorcycle',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'color' => 'required|string',
            'vin' => 'nullable|string|unique:vehicles,vin',
        ]);

        // $identifier is plate_number
        $vehicle = Vehicle::create([
            'plate_number' => $identifier,
            'type' => $request->type,
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
