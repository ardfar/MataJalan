<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleEdit;
use App\Notifications\VehicleEditStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleEditController extends Controller
{
    public function index()
    {
        $edits = VehicleEdit::with(['vehicle:id,plate_number,uuid', 'user:id,name,email'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        return view('admin.vehicle-edits.index', compact('edits'));
    }

    public function downloadDocument(VehicleEdit $edit)
    {
        if (!$edit->document_path || !Storage::disk('local')->exists($edit->document_path)) {
            return back()->with('error', 'Document not found.');
        }
        
        return Storage::disk('local')->download($edit->document_path);
    }

    public function approve(Request $request, VehicleEdit $edit)
    {
        $vehicle = $edit->vehicle;
        $vehicle->update($edit->data);

        $edit->update([
            'status' => 'approved',
            'admin_id' => auth()->id(),
            'admin_comment' => $request->input('comment'),
            'reviewed_at' => now(),
        ]);

        // Notify User
        $edit->user->notify(new VehicleEditStatusUpdated($edit));

        return back()->with('success', 'Vehicle information updated successfully.');
    }

    public function reject(Request $request, VehicleEdit $edit)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $edit->update([
            'status' => 'rejected',
            'admin_id' => auth()->id(),
            'admin_comment' => $request->input('rejection_reason'),
            'reviewed_at' => now(),
        ]);

        // Notify User
        $edit->user->notify(new VehicleEditStatusUpdated($edit));

        return back()->with('success', 'Edit request rejected.');
    }
}
