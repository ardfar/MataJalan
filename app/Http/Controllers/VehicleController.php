<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * Supports search via 'search' query parameter.
     * Search is performed on normalized plate number (case-insensitive, ignoring special chars).
     */
    public function index(Request $request)
    {
        $query = Vehicle::withCount('ratings')
            ->withAvg('ratings', 'rating');

        if ($request->filled('search')) {
            $normalizedSearch = Vehicle::normalizePlate($request->input('search'));
            
            if (!empty($normalizedSearch)) {
                // Use whereRaw to robustly handle spacing/formatting variations on-the-fly
                // Matches "A 549 ZJ" when searching "A549ZJ" or vice versa
                $query->whereRaw("REPLACE(REPLACE(UPPER(plate_number), ' ', ''), '-', '') LIKE ?", ["%{$normalizedSearch}%"]);
            }
        }

        return $query->paginate(20);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $plate_number)
    {
        $query = Vehicle::with(['ratings.user' => function($query) {
                $query->select('id', 'name');
            }])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        // Try exact match first
        $vehicle = (clone $query)->where('plate_number', $plate_number)->first();

        // If not found, try normalized match
        if (!$vehicle) {
            $normalized = Vehicle::normalizePlate($plate_number);
            $vehicle = $query->where('normalized_plate_number', $normalized)->firstOrFail();
        }

        return response()->json($vehicle);
    }
}
