<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MyVehicleController extends Controller
{
    public function index()
    {
        $myVehicles = Vehicle::where('owned_by_user_id', Auth::id())
            ->with(['vehicleUsers' => function($q) {
                // Eager load vehicle users, prioritizing the current user if possible, or just the latest approved
                $q->where('registered_by', Auth::id())->orWhere('status', 'approved');
            }])
            ->latest()
            ->paginate(10);

        return view('my-vehicles.index', compact('myVehicles'));
    }

    public function create()
    {
        return view('my-vehicles.create');
    }

    public function check(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
        ]);

        $plate = $request->plate_number;
        
        // Normalize
        $normalized = Vehicle::normalizePlate($plate);
        
        // Search
        $vehicle = Vehicle::where('plate_number', $plate)
            ->orWhere('normalized_plate_number', $normalized)
            ->first();

        if ($vehicle) {
            return redirect()->route('vehicle.user.create', $vehicle->uuid)
                ->with('info', 'Vehicle found. Please proceed to add driver information.');
        }

        return redirect()->route('my-vehicles.register', ['plate' => $plate])
            ->with('info', 'Vehicle not found. Please register the vehicle details first.');
    }

    public function register($plate)
    {
        // Double check if exists to prevent duplicates if user manually hits this route
        $normalized = Vehicle::normalizePlate($plate);
        $vehicle = Vehicle::where('plate_number', $plate)
            ->orWhere('normalized_plate_number', $normalized)
            ->first();

        if ($vehicle) {
            return redirect()->route('vehicle.user.create', $vehicle->uuid);
        }

        return view('my-vehicles.register', compact('plate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20', // Passed hidden or visible
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'color' => 'required|string|max:30',
            'vin' => 'nullable|string|unique:vehicles,vin|max:50',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => $request->plate_number,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'vin' => $request->vin,
            'owned_by_user_id' => Auth::id(),
        ]);

        // Automatically add record to vehicle_users based on user's account info
        VehicleUser::create([
            'registered_by' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'role_type' => 'personal', // Default to personal for owner
            'driver_name' => Auth::user()->name,
            'status' => 'approved', // Auto-approve owner
            'evidence_path' => 'auto-generated', // Placeholder since it's auto-generated
        ]);

        return redirect()->route('my-vehicles.index')
            ->with('success', 'Vehicle registered and added to your fleet successfully.');
    }
}
