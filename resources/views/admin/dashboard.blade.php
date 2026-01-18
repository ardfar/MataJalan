@extends('layouts.admin')

@section('header', 'SYSTEM_OVERVIEW')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-slate-900 border border-slate-800 p-4 rounded relative overflow-hidden group">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="users" class="w-16 h-16 text-cyan-500"></i>
        </div>
        <div class="text-xs text-slate-500 font-mono uppercase mb-1">Total Operatives</div>
        <div class="text-3xl font-bold text-white font-mono">{{ $stats['total_users'] }}</div>
        <div class="mt-2 text-[10px] text-emerald-400 flex items-center gap-1">
            <i data-lucide="trending-up" class="w-3 h-3"></i> +12% this week
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-4 rounded relative overflow-hidden group">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="shield-check" class="w-16 h-16 text-emerald-500"></i>
        </div>
        <div class="text-xs text-slate-500 font-mono uppercase mb-1">Verified (Tier 1)</div>
        <div class="text-3xl font-bold text-white font-mono">{{ $stats['verified_users'] }}</div>
        <div class="w-full bg-slate-800 h-1 mt-3 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full" style="width: {{ $stats['total_users'] > 0 ? ($stats['verified_users'] / $stats['total_users'] * 100) : 0 }}%"></div>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-4 rounded relative overflow-hidden group">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="file-clock" class="w-16 h-16 text-amber-500"></i>
        </div>
        <div class="text-xs text-slate-500 font-mono uppercase mb-1">Pending KYC</div>
        <div class="text-3xl font-bold text-white font-mono">{{ $stats['pending_kyc'] }}</div>
        @if($stats['pending_kyc'] > 0)
            <a href="{{ route('admin.kyc.index') }}" class="mt-2 inline-block text-[10px] text-amber-400 hover:text-amber-300 underline">Review Queue &rarr;</a>
        @endif
    </div>

    <div class="bg-slate-900 border border-slate-800 p-4 rounded relative overflow-hidden group">
        <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i data-lucide="car" class="w-16 h-16 text-blue-500"></i>
        </div>
        <div class="text-xs text-slate-500 font-mono uppercase mb-1">Tracked Vehicles</div>
        <div class="text-3xl font-bold text-white font-mono">{{ $stats['total_vehicles'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Chart -->
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 p-6 rounded">
        <h3 class="text-sm font-mono text-slate-400 mb-6 flex items-center gap-2">
            <i data-lucide="bar-chart-2" class="w-4 h-4"></i> REGISTRATION_TREND
        </h3>
        <div class="h-64">
            <canvas id="regChart"></canvas>
        </div>
    </div>

    <!-- Audit Log Feed -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded flex flex-col">
        <h3 class="text-sm font-mono text-slate-400 mb-6 flex items-center gap-2">
            <i data-lucide="activity" class="w-4 h-4"></i> RECENT_ACTIVITY
        </h3>
        <div class="space-y-4 overflow-y-auto max-h-[300px] pr-2 custom-scrollbar">
            @forelse($recentLogs as $log)
                <div class="border-l-2 border-slate-700 pl-3 py-1 text-xs">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-cyan-500">{{ $log->user->name ?? 'Unknown' }}</span>
                        <span class="text-[10px] text-slate-600 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-slate-300 mb-1 font-mono text-[10px] uppercase bg-slate-950 inline-block px-1 rounded border border-slate-800">{{ $log->action }}</div>
                    <p class="text-slate-500">{{ Str::limit($log->description, 50) }}</p>
                </div>
            @empty
                <div class="text-center text-slate-600 text-xs italic py-4">No activity recorded.</div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('regChart').getContext('2d');
    const data = @json($registrations);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'New Operatives',
                data: data.map(d => d.count),
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06b6d4'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#1e293b' },
                    ticks: { color: '#64748b' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                }
            }
        }
    });
</script>
@endpush
@endsection
