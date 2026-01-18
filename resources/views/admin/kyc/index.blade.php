@extends('layouts.admin')

@section('header', 'KYC_VERIFICATION_QUEUE')

@section('content')
<div class="bg-slate-900 border border-slate-800 rounded overflow-hidden">
    <table class="w-full text-left text-sm text-slate-400">
        <thead class="bg-slate-950 text-xs uppercase font-mono text-slate-500">
            <tr>
                <th class="px-6 py-3 border-b border-slate-800">Applicant</th>
                <th class="px-6 py-3 border-b border-slate-800">Submitted</th>
                <th class="px-6 py-3 border-b border-slate-800">Documents</th>
                <th class="px-6 py-3 border-b border-slate-800 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @forelse($pendingUsers as $user)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-slate-300">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-200">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 font-mono text-xs">
                    {{ $user->kyc_submitted_at ? $user->kyc_submitted_at->diffForHumans() : 'N/A' }}
                </td>
                <td class="px-6 py-4 font-mono text-xs">
                    @if(!empty($user->kyc_data['document_path']))
                        <span class="text-cyan-400 flex items-center gap-1"><i data-lucide="file-text" class="w-3 h-3"></i> ATTACHED</span>
                    @else
                        <span class="text-slate-600">MISSING</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.kyc.show', $user) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-cyan-400 border border-slate-700 hover:border-cyan-500/50 rounded transition-colors text-xs font-mono uppercase">
                        Review Case &rarr;
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-mono italic">
                    NO PENDING VERIFICATION REQUESTS
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
