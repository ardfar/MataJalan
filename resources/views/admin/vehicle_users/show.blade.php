@extends('layouts.admin')

@section('title', 'Vehicle User Request Details | MATAJALAN_OS')

@section('header', 'REQUEST_DETAILS')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="font-mono text-xl text-slate-100">REQUEST #{{ $vehicleUser->id }}</h2>
        <a href="{{ route('admin.vehicle-users.index') }}" class="text-slate-400 hover:text-cyan-400 font-mono text-sm flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> BACK_TO_LIST
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-lg overflow-hidden shadow-xl">
        <div class="p-6 border-b border-slate-800 bg-slate-950/50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="text-xs text-slate-500 uppercase font-mono mb-1">Status</div>
                    @if($vehicleUser->status === 'pending')
                        <span class="px-3 py-1 bg-yellow-900/30 text-yellow-500 border border-yellow-500/30 rounded font-mono text-xs uppercase font-bold">PENDING_REVIEW</span>
                    @elseif($vehicleUser->status === 'approved')
                        <span class="px-3 py-1 bg-emerald-900/30 text-emerald-500 border border-emerald-500/30 rounded font-mono text-xs uppercase font-bold">APPROVED</span>
                    @else
                        <span class="px-3 py-1 bg-red-900/30 text-red-500 border border-red-500/30 rounded font-mono text-xs uppercase font-bold">REJECTED</span>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-xs text-slate-500 uppercase font-mono mb-1">Submission Date</div>
                    <div class="text-slate-200 font-mono">{{ $vehicleUser->created_at->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- User Info -->
            <div>
                <h3 class="text-sm font-mono font-bold text-cyan-500 uppercase mb-4 border-b border-slate-800 pb-2">APPLICANT_DATA</h3>
                <dl class="space-y-4 font-mono text-sm">
                    <div>
                        <dt class="text-xs text-slate-500 uppercase">Full Name</dt>
                        <dd class="text-slate-200">{{ $vehicleUser->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 uppercase">Email Address</dt>
                        <dd class="text-slate-200">{{ $vehicleUser->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 uppercase">Driver Name</dt>
                        <dd class="text-slate-200">{{ $vehicleUser->driver_name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Vehicle Info -->
            <div>
                <h3 class="text-sm font-mono font-bold text-cyan-500 uppercase mb-4 border-b border-slate-800 pb-2">TARGET_VEHICLE</h3>
                <dl class="space-y-4 font-mono text-sm">
                    <div>
                        <dt class="text-xs text-slate-500 uppercase">Plate Number</dt>
                        <dd class="text-slate-200 font-bold text-lg">
                            <a href="{{ route('vehicle.show', $vehicleUser->vehicle->uuid) }}" class="hover:text-cyan-400 hover:underline">
                                {{ $vehicleUser->vehicle->plate_number }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 uppercase">Model / Make</dt>
                        <dd class="text-slate-200">{{ $vehicleUser->vehicle->make }} {{ $vehicleUser->vehicle->model }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 uppercase">Requested Role</dt>
                        <dd class="text-emerald-400 font-bold uppercase">{{ str_replace('_', ' ', $vehicleUser->role_type) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="p-6 border-t border-slate-800 bg-slate-950/30">
            <h3 class="text-sm font-mono font-bold text-cyan-500 uppercase mb-4">EVIDENCE_DOCUMENTATION</h3>
            <div class="flex items-center gap-4 p-4 bg-slate-900 border border-slate-800 rounded">
                <div class="p-3 bg-slate-800 rounded text-slate-400">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <div class="text-sm text-slate-300 font-mono truncate max-w-md">{{ basename($vehicleUser->evidence_path) }}</div>
                    <div class="text-xs text-slate-500 uppercase">Stored Securely</div>
                </div>
                <a href="{{ route('admin.vehicle-users.download', $vehicleUser) }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-cyan-400 font-mono text-xs uppercase font-bold rounded transition-colors flex items-center gap-2">
                    <i data-lucide="download" class="w-3 h-3"></i> DOWNLOAD
                </a>
            </div>
        </div>

        @if($vehicleUser->status === 'pending')
            <div class="p-6 border-t border-slate-800 bg-slate-900 flex justify-end gap-3">
                <button onclick="openRejectModal({{ $vehicleUser->id }})" class="px-6 py-2 bg-red-900/20 border border-red-500/50 text-red-400 font-mono font-bold text-xs uppercase tracking-wider hover:bg-red-900/40 transition-all">
                    REJECT_REQUEST
                </button>
                <form action="{{ route('admin.vehicle-users.update', $vehicleUser) }}" method="POST" onsubmit="return confirm('Confirm approval?');">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 border border-emerald-500 text-white font-mono font-bold text-xs uppercase tracking-wider hover:bg-emerald-500 transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        APPROVE_REQUEST
                    </button>
                </form>
            </div>
        @endif

        @if($vehicleUser->reviewed_by)
            <div class="p-6 border-t border-slate-800 bg-slate-950/80 text-xs font-mono text-slate-500">
                Reviewed by <span class="text-slate-300">{{ $vehicleUser->reviewer->name }}</span> on {{ $vehicleUser->reviewed_at->format('Y-m-d H:i') }}
                @if($vehicleUser->rejection_reason)
                    <div class="mt-2 text-red-400 border-l-2 border-red-500 pl-2">
                        Reason: {{ $vehicleUser->rejection_reason }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal (Reused) -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded bg-slate-900 border border-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-slate-900 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-base font-semibold leading-6 text-slate-100 font-mono">REJECT_ASSOCIATION</h3>
                        <div class="mt-2">
                            <form id="rejectForm" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <textarea name="rejection_reason" rows="3" class="w-full bg-slate-950 border border-slate-700 rounded text-sm text-slate-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 placeholder-slate-600" required placeholder="Reason for rejection..."></textarea>
                                <div class="mt-5 sm:flex sm:flex-row-reverse gap-2">
                                    <button type="submit" class="inline-flex w-full justify-center rounded bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto font-mono uppercase tracking-wider">Confirm</button>
                                    <button type="button" onclick="closeRejectModal()" class="mt-3 inline-flex w-full justify-center rounded bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-300 shadow-sm ring-1 ring-inset ring-slate-700 hover:bg-slate-700 sm:mt-0 sm:w-auto font-mono uppercase tracking-wider">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(id) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/vehicle-users/${id}/update`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
