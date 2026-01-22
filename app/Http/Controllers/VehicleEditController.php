<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleEdit;
use App\Models\User;
use App\Notifications\VehicleEditSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class VehicleEditController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        return view('vehicle.edit', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        // 1. Validate Plate Number (must match existing)
        if ($request->input('plate_number') !== $vehicle->plate_number) {
            return back()->withErrors(['plate_number' => 'The License Plate number cannot be modified.'])->withInput();
        }

        // 2. Validate Fields
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'color' => 'required|string|max:255',
            'vin' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicles', 'vin')->ignore($vehicle->id),
            ],
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB Max
        ], [
            'document.required' => 'Supporting documentation is required.',
            'document.mimes' => 'The document must be a file of type: pdf, jpg, jpeg, png.',
            'document.max' => 'The document size must not exceed 5MB.',
        ]);

        // 3. Handle File Upload
        $path = $request->file('document')->store('vehicle_documents', 'local'); // Store in storage/app/vehicle_documents (private)

        // 4. Prepare Data (Exclude document from JSON data, add plate_number for context if needed, but usually we just want changes)
        // We will store the form data in the JSON, but remove the file object and plate_number (since it shouldn't change)
        $dataToStore = collect($validated)->except(['document'])->toArray();
        // Ideally we might want to store plate_number in data to verify what was submitted, but since it's readonly, we don't need to "edit" it.
        // However, the admin might want to see context. But the relationship to vehicle_id gives context.
        
        $edit = VehicleEdit::create([
            'vehicle_id' => $vehicle->id,
            'user_id' => auth()->id(),
            'data' => $dataToStore,
            'document_path' => $path,
            'status' => 'pending'
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->orWhere('role', 'superadmin')->get();
        Notification::send($admins, new VehicleEditSubmitted($edit));

        return redirect()->route('vehicle.show', $vehicle->uuid)->with('success', 'Edit request submitted successfully. Pending approval.');
    }
}
