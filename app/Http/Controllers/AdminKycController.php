<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\AuditService;

class AdminKycController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('kyc_status', 'pending')
            ->latest('kyc_submitted_at')
            ->paginate(20);
        return view('admin.kyc.index', compact('pendingUsers'));
    }

    public function show(User $user)
    {
        return view('admin.kyc.show', compact('user'));
    }

    public function approve(User $user)
    {
        $user->update([
            'kyc_status' => 'approved',
            'kyc_verified_at' => now(),
            'role' => User::ROLE_TIER_1, // Auto upgrade to Tier 1
        ]);

        AuditService::log('kyc_approve', "Approved KYC for user: {$user->email}");

        return redirect()->route('admin.kyc.index')->with('status', 'User KYC approved successfully.');
    }

    public function reject(Request $request, User $user)
    {
        $user->update([
            'kyc_status' => 'rejected',
            'kyc_verified_at' => null, // Reset verification timestamp
        ]);

        AuditService::log('kyc_reject', "Rejected KYC for user: {$user->email}");

        return redirect()->route('admin.kyc.index')->with('status', 'User KYC rejected.');
    }

    public function download(User $user)
    {
        if (empty($user->kyc_data['document_path'])) {
            return back()->with('error', 'No document found.');
        }

        AuditService::log('kyc_download', "Downloaded KYC document for user: {$user->email}");

        return Storage::download($user->kyc_data['document_path']);
    }
}
