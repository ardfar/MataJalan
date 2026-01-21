@extends('layouts.admin')

@section('title', 'Rating Details | MATAJALAN_OS')

@section('header')
    RATING_DETAILS <span class="text-slate-500 text-sm ml-2">#{{ $rating->id }}</span>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('admin.ratings.index') }}" class="inline-flex items-center text-xs font-mono text-cyan-500 hover:text-cyan-400 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> BACK_TO_LIST
    </a>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Content & Media -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Content Card -->
            <div class="bg-slate-900 border border-slate-800 rounded p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-800 rounded flex items-center justify-center font-bold text-slate-400 border border-slate-700">
                            {{ substr($rating->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-200">{{ $rating->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs font-mono text-slate-500">{{ $rating->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 bg-slate-950 px-2 py-1 rounded border border-slate-800">
                        <span class="font-mono font-bold text-lg text-slate-200">{{ $rating->rating }}</span>
                        <i data-lucide="star" class="w-4 h-4 fill-amber-500 text-amber-500"></i>
                    </div>
                </div>

                <div class="bg-slate-950 p-4 rounded border border-slate-800 mb-4">
                    <p class="text-slate-300 text-sm leading-relaxed">"{{ $rating->comment }}"</p>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($rating->tags ?? [] as $tag)
                        <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-slate-800 text-slate-400 border border-slate-700">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>

                @if($rating->address)
                    <div class="flex items-center gap-2 text-xs font-mono text-slate-500">
                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                        {{ $rating->address }}
                    </div>
                @endif
            </div>

            <!-- Media Gallery -->
            @if($rating->media->count() > 0)
                <div class="bg-slate-900 border border-slate-800 rounded p-6">
                    <h3 class="text-xs font-mono text-slate-500 uppercase mb-4">Evidence_Media</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($rating->media as $media)
                            <div class="relative group aspect-video bg-slate-950 rounded overflow-hidden border border-slate-800">
                                @if($media->file_type === 'image')
                                    <a href="{{ Storage::url($media->file_path) }}" target="_blank" class="block w-full h-full">
                                        <img src="{{ Storage::url($media->file_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </a>
                                @else
                                    <video src="{{ Storage::url($media->file_path) }}" controls class="w-full h-full object-cover"></video>
                                @endif
                                
                                @if($media->caption)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-slate-300 text-[10px] font-mono p-2 truncate">
                                        {{ $media->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Metadata & Actions -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-slate-900 border border-slate-800 rounded p-6">
                <h3 class="text-xs font-mono text-slate-500 uppercase mb-4">Status_Check</h3>
                
                <div class="flex items-center justify-between mb-6">
                    <span class="text-sm text-slate-400">Current State</span>
                    @if($rating->status === 'approved')
                        <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">Approved</span>
                    @elseif($rating->status === 'pending')
                        <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-yellow-900/30 text-yellow-400 border border-yellow-500/30">Pending</span>
                    @else
                        <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-red-900/30 text-red-400 border border-red-500/30">Rejected</span>
                    @endif
                </div>

                <div class="space-y-3 pt-4 border-t border-slate-800">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Honesty Declaration</span>
                        @if($rating->is_honest)
                            <span class="text-emerald-500 font-mono flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> VERIFIED
                            </span>
                        @else
                            <span class="text-slate-600 font-mono">N/A</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Target Vehicle</span>
                        <a href="{{ route('vehicle.show', $rating->vehicle->uuid) }}" class="text-cyan-500 hover:text-cyan-400 font-mono hover:underline">
                            {{ $rating->vehicle->plate_number }}
                        </a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 pt-6 border-t border-slate-800 flex gap-2">
                    @if($rating->status !== 'approved')
                        <form action="{{ route('admin.ratings.approve', $rating) }}" method="POST" class="flex-1" onsubmit="return confirm('Confirm approval?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-mono uppercase rounded transition-colors shadow-lg shadow-emerald-900/20">
                                Approve
                            </button>
                        </form>

                       <button onclick="openRejectModal({{ $rating->id }})" class="flex-1 py-2 bg-red-900/20 hover:bg-red-900/40 border border-red-500/30 text-red-400 text-xs font-mono uppercase rounded transition-colors">
                            Reject
                        </button>
                    @endif
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="bg-slate-900 border border-slate-800 rounded p-6">
                <h3 class="text-xs font-mono text-slate-500 uppercase mb-4">Audit_Log</h3>
                <div class="relative pl-4 border-l border-slate-800 space-y-6">
                    @forelse($rating->adminActions as $action)
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full {{ $action->action_type === 'approve_rating' ? 'bg-emerald-500' : ($action->action_type === 'reject_rating' ? 'bg-red-500' : 'bg-slate-600') }}"></div>
                            <div class="text-xs text-slate-300 font-bold mb-1">
                                {{ strtoupper(str_replace('_', ' ', $action->action_type)) }}
                            </div>
                            <div class="text-[10px] font-mono text-slate-500 mb-1">
                                {{ $action->created_at->format('Y-m-d H:i:s') }}
                            </div>
                            <div class="text-xs text-slate-400">
                                By: <span class="text-cyan-500">{{ $action->user->name ?? 'System' }}</span>
                            </div>
                            @if(isset($action->details['reason']))
                                <div class="mt-2 p-2 bg-red-950/30 border border-red-500/20 rounded text-[10px] text-red-300 font-mono">
                                    "{{ $action->details['reason'] }}"
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-600 italic">No actions recorded.</div>
                    @endforelse
                </div>
            </div>
        </div>
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
                        <h3 class="text-base font-semibold leading-6 text-slate-100 font-mono">REJECT_RATING</h3>
                        <div class="mt-2">
                            <form id="rejectForm" method="POST">
                                @csrf
                                @method('PATCH')
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
    function openRejectModal(ratingId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/ratings/${ratingId}/reject`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
