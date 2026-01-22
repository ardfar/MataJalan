<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use App\Models\Rating;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    protected function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::where('kyc_status', 'approved')->count(),
            'pending_kyc' => User::where('kyc_status', 'pending')->count(),
            'total_vehicles' => Vehicle::count(),
            'pending_ratings' => Rating::where('status', 'pending')->count(),
            'pending_vehicle_users' => VehicleUser::where('status', 'pending')->count(),
        ];

        // Recent Audit Logs
        $recentLogs = AuditLog::with('user')->latest()->take(5)->get();

        // Registration Trend
        $registrations = User::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Return the admin view (we reuse the one we built for /admin)
        return view('admin.dashboard', compact('stats', 'recentLogs', 'registrations'));
    }

    protected function userDashboard($user)
    {
        // Personalized content for the user
        // e.g., Their rating history, their KYC status details, etc.
        
        // Mocking some user-specific stats
        $userStats = [
            'ratings_submitted' => $user->ratings()->count(),
            'kyc_status' => $user->kyc_status,
            'joined_date' => $user->created_at->format('Y-m-d'),
        ];

        // Get their recent ratings
        $recentRatings = $user->ratings()->with('vehicle')->latest()->take(5)->get();

        return view('dashboard', compact('userStats', 'recentRatings'));
    }
}
