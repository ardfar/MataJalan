@extends('layouts.admin')

@section('title', 'KYC Details | MATAJALAN_OS')

@section('header', 'CASE_REVIEW: ' . strtoupper($user->name))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Applicant Details -->
    <div class="bg-slate-900 border border-slate-800 rounded p-6 h-fit">
        <h3 class="text-xs font-mono font-bold text-slate-500 uppercase mb-4">Identity Profile</h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-[10px] text-slate-600 uppercase font-mono">Full Name</label>
                <div class="text-slate-200 font-bold">{{ $user->name }}</div>
            </div>
            <div>
                <label class="block text-[10px] text-slate-600 uppercase font-mono">Email Frequency</label>
                <div class="text-slate-200 font-mono text-sm">{{ $user->email }}</div>
            </div>
            <div>
                <label class="block text-[10px] text-slate-600 uppercase font-mono">Submission Time</label>
                <div class="text-slate-200 font-mono text-sm">{{ $user->kyc_submitted_at ? $user->kyc_submitted_at->toDayDateTimeString() : 'N/A' }}</div>
            </div>
            <div>
                <label class="block text-[10px] text-slate-600 uppercase font-mono">Provided Details</label>
                <div class="bg-slate-950 p-3 rounded border border-slate-800 text-xs font-mono text-slate-400">
                    {{-- Assuming additional fields exist in kyc_data, mock for now if simple --}}
                    @if(isset($user->kyc_data['notes']))
                        {{ $user->kyc_data['notes'] }}
                    @else
                        NO_ADDITIONAL_NOTES_PROVIDED
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview & Actions -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900 border border-slate-800 rounded p-6">
            <h3 class="text-xs font-mono font-bold text-slate-500 uppercase mb-4">Submitted Evidence</h3>
            
            @if(!empty($user->kyc_data['document_path']))
                <div class="flex items-center justify-between bg-slate-950 p-4 border border-slate-800 rounded mb-4">
                    <div class="flex items-center gap-3">
                        <i data-lucide="file-check" class="w-8 h-8 text-cyan-500"></i>
                        <div>
                            <div class="text-sm text-slate-300 font-mono">ID_VERIFICATION_DOC.PDF</div>
                            <div class="text-[10px] text-slate-600 uppercase">ENCRYPTED STORAGE</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.kyc.download', $user) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-mono uppercase rounded border border-slate-700 transition-colors flex items-center gap-2">
                        <i data-lucide="download" class="w-3 h-3"></i> Download
                    </a>
                </div>
                
                <div class="aspect-video bg-slate-950 border border-slate-800 flex items-center justify-center text-slate-600 font-mono text-xs uppercase rounded">
                    [PREVIEW_UNAVAILABLE_FOR_ENCRYPTED_FILE_TYPE]
                </div>
            @else
                <div class="p-8 text-center border border-dashed border-slate-700 rounded text-slate-500 font-mono text-sm">
                    NO DOCUMENT UPLOADED
                </div>
            @endif
        </div>

        <!-- Decision Console -->
        <div class="bg-slate-900 border border-slate-800 rounded p-6">
            <h3 class="text-xs font-mono font-bold text-slate-500 uppercase mb-4">Adjudication Console</h3>
            
            <div class="flex gap-4">
                <form action="{{ route('admin.kyc.approve', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full py-3 bg-emerald-900/20 border border-emerald-500/50 hover:bg-emerald-500 hover:text-white text-emerald-400 font-mono font-bold uppercase rounded transition-all flex items-center justify-center gap-2 group">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Approve & Upgrade
                    </button>
                </form>

                <form action="{{ route('admin.kyc.reject', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full py-3 bg-red-900/20 border border-red-500/50 hover:bg-red-500 hover:text-white text-red-400 font-mono font-bold uppercase rounded transition-all flex items-center justify-center gap-2 group" onclick="return confirm('Reject this application?');">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
