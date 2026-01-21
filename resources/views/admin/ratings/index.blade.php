@extends('layouts.admin')

@section('title', 'Rating Moderation | MATAJALAN_OS')

@section('header', 'RATING_MODERATION')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded p-6">
        <form method="GET" action="{{ route('admin.ratings.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-mono text-slate-500 uppercase mb-2">Status</label>
                <select name="status" id="status" class="w-full bg-slate-950 border border-slate-700 rounded text-sm text-slate-300 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-xs font-mono text-slate-500 uppercase mb-2">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full bg-slate-950 border border-slate-700 rounded text-sm text-slate-300 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>

            <!-- Date To -->
            <div>
                <label for="date_to" class="block text-xs font-mono text-slate-500 uppercase mb-2">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full bg-slate-950 border border-slate-700 rounded text-sm text-slate-300 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>

            <!-- User Search -->
            <div>
                <label for="search_user" class="block text-xs font-mono text-slate-500 uppercase mb-2">User (Name/Email)</label>
                <input type="text" name="search_user" id="search_user" value="{{ request('search_user') }}" placeholder="Search user..." class="w-full bg-slate-950 border border-slate-700 rounded text-sm text-slate-300 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 placeholder-slate-600">
            </div>

            <!-- Rating Value -->
            <div>
                <label for="rating_value" class="block text-xs font-mono text-slate-500 uppercase mb-2">Rating</label>
                <select name="rating_value" id="rating_value" class="w-full bg-slate-950 border border-slate-700 rounded text-sm text-slate-300 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="">All Ratings</option>
                    @foreach(range(1, 5) as $r)
                        <option value="{{ $r }}" {{ request('rating_value') == $r ? 'selected' : '' }}>{{ $r }} Stars</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5 flex justify-end gap-2 mt-2">
                <a href="{{ route('admin.ratings.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-mono uppercase rounded transition-colors">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-mono uppercase rounded transition-colors shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    <!-- Ratings List -->
    <div class="bg-slate-900 border border-slate-800 rounded overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-xs font-mono text-slate-500 uppercase">
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium">User</th>
                        <th class="px-6 py-4 font-medium">Vehicle</th>
                        <th class="px-6 py-4 font-medium">Rating</th>
                        <th class="px-6 py-4 font-medium">Content</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($ratings as $rating)
                        <tr class="hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-400 font-mono">
                                {{ $rating->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                <div class="font-bold">{{ $rating->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-slate-500">{{ $rating->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('vehicle.show', $rating->vehicle->uuid) }}" class="text-cyan-400 hover:text-cyan-300 font-mono hover:underline" target="_blank">
                                    {{ $rating->vehicle->plate_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <span class="font-mono font-bold text-slate-200">{{ $rating->rating }}</span>
                                    <i data-lucide="star" class="w-3 h-3 fill-amber-500 text-amber-500"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400 max-w-xs">
                                <p class="truncate mb-2 text-slate-300">"{{ $rating->comment }}"</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($rating->tags ?? [] as $tag)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono uppercase bg-slate-800 text-slate-400 border border-slate-700">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                                @if($rating->media->count() > 0)
                                    <div class="mt-2 text-[10px] text-cyan-500 font-mono flex items-center gap-1">
                                        <i data-lucide="paperclip" class="w-3 h-3"></i>
                                        {{ $rating->media->count() }} ATTACHMENT(S)
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($rating->status === 'approved')
                                    <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">Approved</span>
                                @elseif($rating->status === 'pending')
                                    <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-yellow-900/30 text-yellow-400 border border-yellow-500/30">Pending</span>
                                @else
                                    <span class="px-2 py-1 rounded text-[10px] font-mono uppercase bg-red-900/30 text-red-400 border border-red-500/30">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.ratings.show', $rating) }}" class="p-2 hover:bg-slate-700 rounded text-slate-400 hover:text-white transition-colors" title="View Details">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    
                                    @if($rating->status === 'pending')
                                        <form action="{{ route('admin.ratings.approve', $rating) }}" method="POST" class="inline" onsubmit="return confirm('Confirm approval?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 hover:bg-emerald-900/30 rounded text-emerald-500 hover:text-emerald-400 transition-colors" title="Approve">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        
                                        <button onclick="openRejectModal({{ $rating->id }})" class="p-2 hover:bg-red-900/30 rounded text-red-500 hover:text-red-400 transition-colors" title="Reject">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-mono">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                                NO_DATA_FOUND
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-800 bg-slate-950">
            {{ $ratings->links('vendor.pagination.cyberpunk') }}
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeRejectModal()"></div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded bg-slate-900 border border-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-slate-900 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <i data-lucide="alert-circle" class="w-6 h-6 text-red-500"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 text-slate-100 font-mono">REJECT_RATING</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-400 mb-4">
                                    Please provide a reason for rejecting this rating. This will be visible to the user.
                                </p>
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
