<?php

namespace App\Http\Controllers;

use App\Models\AdminAction;
use App\Models\Rating;
use App\Notifications\RatingStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRatingController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::query()->with(['user', 'vehicle', 'media']);

        // Filter by status, default to pending
        $status = $request->input('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $ratings = $query->latest()->paginate(20)->withQueryString();

        return view('admin.ratings.index', compact('ratings', 'status'));
    }

    public function approve(Request $request, Rating $rating)
    {
        $rating->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $this->logAction('approve_rating', $rating);

        // Notify User
        if ($rating->user) {
            $rating->user->notify(new RatingStatusUpdated($rating));
        }

        return back()->with('success', 'Rating approved successfully.');
    }

    public function reject(Request $request, Rating $rating)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $rating->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(), // We track who rejected it too
            'approved_at' => now(), // Time of decision
            'rejection_reason' => $request->rejection_reason,
        ]);

        $this->logAction('reject_rating', $rating, ['reason' => $request->rejection_reason]);

        // Notify User
        if ($rating->user) {
            $rating->user->notify(new RatingStatusUpdated($rating));
        }

        return back()->with('success', 'Rating rejected.');
    }

    protected function logAction($action, $target, $details = [])
    {
        AdminAction::create([
            'user_id' => Auth::id(),
            'action_type' => $action,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'details' => $details,
        ]);
    }
}
