<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleUser;
use App\Models\Rating;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
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

        // Registration Trend (Mock or Real)
        // Simple 7-day trend
        $registrations = User::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLogs', 'registrations'));
    }
}
