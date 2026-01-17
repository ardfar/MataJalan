<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKycController extends Controller
{
    public function index()
    {
        $users = User::where('kyc_status', 'pending')->orderBy('kyc_submitted_at', 'desc')->get();
        return view('admin.kyc.index', compact('users'));
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
        ]);

        return redirect()->route('admin.kyc.index')->with('status', 'User KYC approved successfully.');
    }

    public function reject(User $user)
    {
        // Optionally delete the file if rejected, or keep it for records.
        // For now, let's keep it but mark as rejected.
        $user->update([
            'kyc_status' => 'rejected',
            'kyc_verified_at' => null, // Clear if previously verified
        ]);

        return redirect()->route('admin.kyc.index')->with('status', 'User KYC rejected.');
    }
    
    public function download(User $user)
    {
         if (!$user->kyc_data || !isset($user->kyc_data['document_path'])) {
             abort(404);
         }
         
         return Storage::download($user->kyc_data['document_path']);
    }
}
