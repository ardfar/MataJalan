<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function index()
    {
        return view('kyc.index', [
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|in:passport,id_card,driving_license',
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('document_file')->store('kyc-documents');

        $user = auth()->user();
        $user->update([
            'kyc_status' => 'pending',
            'kyc_data' => [
                'document_type' => $request->document_type,
                'document_path' => $path,
            ],
            'kyc_submitted_at' => now(),
        ]);

        return redirect()->route('kyc.index')->with('status', 'KYC submitted successfully. Please wait for admin verification.');
    }
}
