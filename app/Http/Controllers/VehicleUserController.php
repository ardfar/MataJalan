<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use App\Notifications\VehicleUserStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleUserController extends Controller
{
    // User: Show form to claim/register as user
    public function create(Vehicle $vehicle)
    {
        return view('vehicle.user.create', compact('vehicle'));
    }

    // User: Store the request
    public function store(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'role_type' => 'required|in:corporate,personal,taxi,e_hauling,government',
            'driver_name' => 'required|string|max:255',
            'evidence' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);

        $path = $request->file('evidence')->store('vehicle-user-evidence', 'private'); // Store securely

        $vehicleUser = VehicleUser::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'role_type' => $request->role_type,
            'driver_name' => $request->driver_name,
            'evidence_path' => $path,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'SUBMIT_VEHICLE_USER',
            'description' => "Submitted vehicle user request for {$vehicle->plate_number} as {$request->role_type}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('vehicle.show', $vehicle->uuid)
            ->with('success', 'Vehicle user application submitted successfully. Pending review.');
    }

    // Admin: List pending requests
    public function index()
    {
        $this->authorizeAdmin();

        $pendingRequests = VehicleUser::with(['user', 'vehicle'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.vehicle_users.index', compact('pendingRequests'));
    }

    // Admin: Show detail/review page
    public function show(VehicleUser $vehicleUser)
    {
        $this->authorizeAdmin();

        return view('admin.vehicle_users.show', compact('vehicleUser'));
    }

    // Admin: Approve/Reject
    public function update(Request $request, VehicleUser $vehicleUser)
    {
        $this->authorizeAdmin();

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);

        $vehicleUser->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
        ]);

        // Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'REVIEW_VEHICLE_USER',
            'description' => "{$request->status} vehicle user request #{$vehicleUser->id} for {$vehicleUser->vehicle->plate_number}",
            'ip_address' => $request->ip(),
        ]);

        // Notify User
        $vehicleUser->user->notify(new VehicleUserStatusUpdated($vehicleUser));

        return redirect()->route('admin.vehicle-users.index')
            ->with('success', "Request has been {$request->status}.");
    }

    // Helper for Admin check
    protected function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }
    
    public function downloadEvidence(VehicleUser $vehicleUser)
    {
        $this->authorizeAdmin();
        
        if (!Storage::disk('private')->exists($vehicleUser->evidence_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download($vehicleUser->evidence_path);
    }
}
