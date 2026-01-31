<?php

namespace App\Http\Controllers;

use App\Models\VehicleSpec;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        // Filter by Type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
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
        $carBrands = VehicleSpec::AVAILABLE_CAR_BRANDS;
        $motorcycleBrands = VehicleSpec::AVAILABLE_MOTORCYCLE_BRANDS;
        $commercialBrands = VehicleSpec::AVAILABLE_COMMERCIAL_BRANDS;
        $carCategories = VehicleSpec::CAR_CATEGORIES;
        $motorcycleCategories = VehicleSpec::MOTORCYCLE_CATEGORIES;
        $truckCategories = VehicleSpec::TRUCK_CATEGORIES;
        $busCategories = VehicleSpec::BUS_CATEGORIES;

        return view('admin.vehicle-specs.create', compact(
            'carBrands', 'motorcycleBrands', 'commercialBrands', 
            'carCategories', 'motorcycleCategories', 'truckCategories', 'busCategories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:car,motorcycle,truck,bus',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'variant' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'engine_cc' => 'nullable|integer',
            'battery_kwh' => 'nullable|numeric',
            'horsepower' => 'required|integer',
            'torque' => 'required|integer',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'seat_capacity' => 'required|integer',
            'cargo_capacity_kg' => 'nullable|integer',
            'gvwr_kg' => 'nullable|integer',
            'axle_count' => 'nullable|integer',
        ]);

        VehicleSpec::create($request->all());

        return redirect()->route('admin.vehicle-specs.index')
            ->with('success', 'Vehicle Spec created successfully.');
    }

    public function edit(VehicleSpec $vehicleSpec)
    {
        $carBrands = VehicleSpec::AVAILABLE_CAR_BRANDS;
        $motorcycleBrands = VehicleSpec::AVAILABLE_MOTORCYCLE_BRANDS;
        $commercialBrands = VehicleSpec::AVAILABLE_COMMERCIAL_BRANDS;
        $carCategories = VehicleSpec::CAR_CATEGORIES;
        $motorcycleCategories = VehicleSpec::MOTORCYCLE_CATEGORIES;
        $truckCategories = VehicleSpec::TRUCK_CATEGORIES;
        $busCategories = VehicleSpec::BUS_CATEGORIES;

        // Get brands based on type, or all if not set
        $brands = VehicleSpec::getAvailableBrands($vehicleSpec->type);
        
        return view('admin.vehicle-specs.edit', compact(
            'vehicleSpec', 'brands', 'carBrands', 'motorcycleBrands', 'commercialBrands',
            'carCategories', 'motorcycleCategories', 'truckCategories', 'busCategories'
        ));
    }

    public function update(Request $request, VehicleSpec $vehicleSpec)
    {
        $request->validate([
            'type' => 'required|in:car,motorcycle,truck,bus',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'variant' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'engine_cc' => 'nullable|integer',
            'battery_kwh' => 'nullable|numeric',
            'horsepower' => 'required|integer',
            'torque' => 'required|integer',
            'transmission' => 'required|string|max:50',
            'fuel_type' => 'required|string|max:50',
            'seat_capacity' => 'required|integer',
            'cargo_capacity_kg' => 'nullable|integer',
            'gvwr_kg' => 'nullable|integer',
            'axle_count' => 'nullable|integer',
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
