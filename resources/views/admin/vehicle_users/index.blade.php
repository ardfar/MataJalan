@extends('layouts.admin')

@section('title', 'Vehicle User Requests | MATAJALAN_OS')

@section('header', 'PENDING_USER_LINKS')

@section('content')
<div class="space-y-6">
    <div class="bg-slate-900 border border-slate-800 rounded overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-xs font-mono text-slate-500 uppercase">
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium">User</th>
                        <th class="px-6 py-4 font-medium">Vehicle</th>
                        <th class="px-6 py-4 font-medium">Role Details</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($pendingRequests as $req)
                        <tr class="hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-400 font-mono">
                                {{ $req->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                <div class="font-bold">{{ $req->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500">{{ $req->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('vehicle.show', $req->vehicle->uuid) }}" class="text-cyan-400 hover:text-cyan-300 font-mono hover:underline" target="_blank">
                                    {{ $req->vehicle->plate_number }}
                                </a>
                                <div class="text-xs text-slate-500">{{ $req->vehicle->model }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono">
                                <div class="space-y-2">
                                    <div class="grid grid-cols-[80px_1fr] gap-2">
                                        <span class="text-xs text-slate-500 uppercase">ROLE:</span>
                                        <span class="text-emerald-400 font-bold uppercase">{{ str_replace('_', ' ', $req->role_type) }}</span>
                                    </div>
                                    <div class="grid grid-cols-[80px_1fr] gap-2">
                                        <span class="text-xs text-slate-500 uppercase">DRIVER:</span>
                                        <span class="text-slate-300">{{ $req->driver_name }}</span>
                                    </div>
                                    
                                    <div class="mt-2 pt-2 border-t border-slate-800">
                                        <a href="{{ route('admin.vehicle-users.download', $req) }}" class="text-xs flex items-center gap-1 text-cyan-500 hover:text-cyan-400 font-mono" target="_blank">
                                            <i data-lucide="file-check" class="w-3 h-3"></i>
                                            VERIFY_EVIDENCE_DOC
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.vehicle-users.update', $req) }}" method="POST" onsubmit="return confirm('Approve user association?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="p-2 hover:bg-emerald-900/30 rounded text-emerald-500 hover:text-emerald-400 transition-colors" title="Approve">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    
                                    <button onclick="openRejectModal({{ $req->id }})" class="p-2 hover:bg-red-900/30 rounded text-red-500 hover:text-red-400 transition-colors" title="Reject">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-mono">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                                NO_PENDING_ASSOCIATIONS
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-800 bg-slate-950">
            {{ $pendingRequests->links() }}
        </div>
    </div>
</div>

<!-- Reject Modal -->
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
