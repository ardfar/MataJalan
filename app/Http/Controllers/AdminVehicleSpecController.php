<?php

namespace App\Http\Controllers;

use App\Models\VehicleSpec;
use Illuminate\Http\Request;

class AdminVehicleSpecController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleSpec::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('variant', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by Fuel Type
        if ($request->has('fuel_type') && $request->fuel_type) {
            $query->where('fuel_type', $request->fuel_type);
        }

        $specs = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.vehicle-specs.index', compact('specs'));
    }

    public function create()
    {
        return view('admin.vehicle-specs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'variant' => 'required|string|max:100',
            'category' => 'required|in:MPV,SUV,LCGC,Sedan,Hatchback,EV,Commercial',
            'engine_cc' => 'nullable|integer',
            'battery_kwh' => 'nullable|numeric',
            'horsepower' => 'required|integer',
            'torque' => 'required|integer',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'seat_capacity' => 'required|integer',
        ]);

        VehicleSpec::create($request->all());

        return redirect()->route('admin.vehicle-specs.index')
            ->with('success', 'Vehicle Spec created successfully.');
    }

    public function edit(VehicleSpec $vehicleSpec)
    {
        $brands = VehicleSpec::AVAILABLE_BRANDS;
        return view('admin.vehicle-specs.edit', compact('vehicleSpec', 'brands'));
    }

    public function update(Request $request, VehicleSpec $vehicleSpec)
    {
        $request->validate([
            'brand' => ['required', 'string', Rule::in(VehicleSpec::AVAILABLE_BRANDS)],
            'model' => 'required|string|max:100',
            'variant' => 'required|string|max:100',
            'category' => 'required|in:MPV,SUV,LCGC,Sedan,Hatchback,EV,Commercial',
            'engine_cc' => 'nullable|integer',
            'battery_kwh' => 'nullable|numeric',
            'horsepower' => 'required|integer',
            'torque' => 'required|integer',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'seat_capacity' => 'required|integer',
        ]);

        $vehicleSpec->update($request->all());

        return redirect()->route('admin.vehicle-specs.index')
            ->with('success', 'Vehicle Spec updated successfully.');
    }

    public function destroy(VehicleSpec $vehicleSpec)
    {
        $vehicleSpec->delete();

        return redirect()->route('admin.vehicle-specs.index')
            ->with('success', 'Vehicle Spec deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:vehicle_specs,id',
        ]);

        VehicleSpec::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.vehicle-specs.index')
            ->with('success', 'Selected specs deleted successfully.');
    }
}
