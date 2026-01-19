<x-app-layout>
    <x-slot name="header">
        {{ __('OPERATIVE_DASHBOARD') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Panel -->
            <div class="bg-slate-900 border border-slate-800 overflow-hidden shadow-sm sm:rounded-lg relative group">
                <div class="absolute top-0 right-0 p-2 opacity-50">
                    <div class="flex gap-1">
                        <div class="w-1 h-1 bg-cyan-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-cyan-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-cyan-500 rounded-full"></div>
                    </div>
                </div>
                
                <div class="p-6 text-slate-300 font-mono">
                    <div class="flex items-center gap-3 mb-4">
                        <i data-lucide="terminal" class="text-cyan-500"></i>
                        <h3 class="text-lg font-bold text-slate-100">SYSTEM_MESSAGE</h3>
                    </div>
                    <p class="text-emerald-400 mb-2">> AUTHENTICATION_SUCCESSFUL</p>
                    <p class="mb-4">> WELCOME_OPERATIVE: <span class="text-cyan-400">{{ Auth::user()->name }}</span></p>
                    <div class="text-slate-500 text-sm flex gap-4">
                        <span>Role: {{ strtoupper(Auth::user()->role) }}</span>
                        <span>KYC: {{ strtoupper(Auth::user()->kyc_status) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stats / Quick Actions -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Personal Stats -->
                    <div class="bg-slate-900 border border-slate-800 p-6 rounded">
                        <h4 class="text-slate-100 font-bold font-mono mb-4 text-xs uppercase">Operative Stats</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm font-mono">
                                <span class="text-slate-500">Reports Filed</span>
                                <span class="text-cyan-400">{{ $userStats['ratings_submitted'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-mono">
                                <span class="text-slate-500">Joined</span>
                                <span class="text-slate-300">{{ $userStats['joined_date'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-mono">
                                <span class="text-slate-500">Clearance</span>
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-xs {{ Auth::user()->role === 'tier_1' ? 'text-emerald-400' : 'text-slate-400' }}">
                                    {{ strtoupper(Auth::user()->role) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="grid grid-cols-1 gap-4">
                         <a href="{{ route('home') }}" class="bg-slate-900 border border-slate-800 p-4 rounded hover:border-cyan-500/50 transition-colors group flex items-center gap-3">
                            <i data-lucide="eye" class="text-slate-500 group-hover:text-cyan-400"></i>
                            <div>
                                <h4 class="text-slate-100 font-bold font-mono text-sm">SURVEILLANCE</h4>
                                <p class="text-[10px] text-slate-500 font-mono">Access live grid</p>
                            </div>
                        </a>

                        <a href="{{ route('kyc.index') }}" class="bg-slate-900 border border-slate-800 p-4 rounded hover:border-cyan-500/50 transition-colors group flex items-center gap-3">
                            <i data-lucide="shield-check" class="text-slate-500 group-hover:text-cyan-400"></i>
                            <div>
                                <h4 class="text-slate-100 font-bold font-mono text-sm">VERIFICATION</h4>
                                <p class="text-[10px] text-slate-500 font-mono">Manage ID status</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="bg-slate-900 border border-slate-800 p-4 rounded hover:border-cyan-500/50 transition-colors group flex items-center gap-3">
                            <i data-lucide="settings" class="text-slate-500 group-hover:text-cyan-400"></i>
                            <div>
                                <h4 class="text-slate-100 font-bold font-mono text-sm">SETTINGS</h4>
                                <p class="text-[10px] text-slate-500 font-mono">Config profile</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div class="md:col-span-2 bg-slate-900 border border-slate-800 p-6 rounded">
                    <h4 class="text-slate-100 font-bold font-mono mb-6 text-xs uppercase flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-cyan-500"></i>
                        Recent Activity Log
                    </h4>

                    <div class="space-y-4">
                        @forelse($recentRatings as $rating)
                            <div class="flex gap-4 p-4 bg-slate-950/50 border border-slate-800/50 rounded">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 rounded bg-slate-800 flex items-center justify-center text-cyan-500 font-bold font-mono">
                                        {{ $rating->rating }}
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-bold text-slate-200 font-mono">{{ $rating->vehicle->plate_number }}</span>
                                        <span class="text-[10px] text-slate-500">{{ $rating->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mb-2">"{{ Str::limit($rating->comment, 60) }}"</p>
                                    @if($rating->tags)
                                        <div class="flex gap-2">
                                            @foreach($rating->tags as $tag)
                                                <span class="text-[10px] px-1.5 py-0.5 bg-slate-800 text-slate-400 rounded border border-slate-700">#{{ strtoupper($tag) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 border border-dashed border-slate-800 rounded">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-600 mx-auto mb-2"></i>
                                <p class="text-slate-500 font-mono text-xs">NO_ACTIVITY_RECORDED</p>
                                <a href="{{ route('home') }}" class="text-cyan-500 hover:underline text-xs font-mono mt-2 inline-block">START_SURVEILLANCE &rarr;</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
