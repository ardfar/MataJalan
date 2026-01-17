<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Vehicle::withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->paginate(20);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $plate_number)
    {
        $vehicle = Vehicle::where('plate_number', $plate_number)
            ->with(['ratings.user' => function($query) {
                $query->select('id', 'name');
            }])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->firstOrFail();

        return response()->json($vehicle);
    }
}
