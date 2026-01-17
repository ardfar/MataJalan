<x-app-layout>
    <x-slot name="header">
        {{ __('TARGET_PROFILE') }} // {{ $plate_number }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Profile Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-2xl overflow-hidden mb-6 relative">
                <!-- Status Bar -->
                <div class="h-1 w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500"></div>
                
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h1 class="text-4xl font-mono font-bold text-slate-100 tracking-tight">{{ $plate_number }}</h1>
                                @if($vehicle && $vehicle->ratings_avg_rating && $vehicle->ratings_avg_rating < 2.5)
                                    <span class="px-2 py-1 bg-red-900/30 border border-red-500/50 text-red-400 text-[10px] font-mono font-bold rounded">HIGH_THREAT</span>
                                @else
                                    <span class="px-2 py-1 bg-emerald-900/30 border border-emerald-500/50 text-emerald-400 text-[10px] font-mono font-bold rounded">SAFE_LEVEL</span>
                                @endif
                            </div>
                            <p class="text-xs font-mono text-slate-500 uppercase">Vehicle Identification Number // Verified in Database</p>
                        </div>
                        
                        <a href="{{ route('vehicle.rate', $plate_number) }}" class="inline-flex items-center justify-center px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-wider transition-all clip-path-polygon hover:shadow-[0_0_15px_rgba(6,182,212,0.5)]">
                            <i data-lucide="file-plus" class="w-4 h-4 mr-2"></i>
                            SUBMIT_REPORT
                        </a>
                    </div>

                    @if($vehicle)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-slate-950 border border-slate-800 p-4 rounded text-center">
                                <div class="text-xs text-slate-500 font-mono uppercase mb-1">Safety Score</div>
                                <div class="text-3xl font-bold text-white font-mono">{{ round($vehicle->ratings_avg_rating * 20) }}<span class="text-sm text-slate-600">/100</span></div>
                            </div>
                            <div class="bg-slate-900 border border-slate-800 p-4 rounded text-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-amber-500/5 opacity-0 hover:opacity-100 transition-opacity"></div>
                                <div class="text-xs text-slate-500 font-mono uppercase mb-1">Average Rating</div>
                                <div class="text-3xl font-bold text-amber-400 font-mono flex items-center justify-center gap-2">
                                    {{ number_format($vehicle->ratings_avg_rating, 1) }}
                                    <i data-lucide="star" class="w-5 h-5 fill-amber-400 text-amber-400"></i>
                                </div>
                            </div>
                            <div class="bg-slate-950 border border-slate-800 p-4 rounded text-center">
                                <div class="text-xs text-slate-500 font-mono uppercase mb-1">Total Reports</div>
                                <div class="text-3xl font-bold text-cyan-400 font-mono">{{ $vehicle->ratings_count }}</div>
                            </div>
                        </div>

                        <div class="border-t border-slate-800 pt-8">
                            <h2 class="text-lg font-mono font-bold text-slate-100 mb-6 flex items-center gap-2">
                                <i data-lucide="list" class="text-slate-500"></i>
                                INCIDENT_LOGS
                            </h2>
                            
                            <div class="space-y-4">
                                @foreach($vehicle->ratings as $rating)
                                    <div class="bg-slate-950 border border-slate-800 p-5 rounded hover:border-slate-700 transition-colors group">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded bg-slate-900 flex items-center justify-center font-mono font-bold text-slate-500 border border-slate-800">
                                                    {{ substr($rating->user->name ?? 'A', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-mono font-bold text-slate-300 text-sm">{{ $rating->user->name ?? 'Anonymous Agent' }}</div>
                                                    <div class="text-[10px] font-mono text-slate-600 uppercase">{{ $rating->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                            <div class="flex gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star" class="w-3 h-3 {{ $i <= $rating->rating ? 'fill-amber-500 text-amber-500' : 'text-slate-800' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        <p class="text-slate-400 text-sm leading-relaxed mb-4 font-mono pl-11 border-l border-slate-800 ml-4">
                                            "{{ $rating->comment }}"
                                        </p>
                                        
                                        @if($rating->tags)
                                            <div class="flex flex-wrap gap-2 pl-11 ml-4">
                                                @foreach($rating->tags as $tag)
                                                    <span class="px-2 py-0.5 bg-cyan-900/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-mono uppercase rounded">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16 border-2 border-dashed border-slate-800 rounded-lg bg-slate-900/50">
                            <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="database" class="w-8 h-8 text-slate-500"></i>
                            </div>
                            <p class="text-lg font-mono text-slate-300 mb-2">NO DATA FOUND</p>
                            <p class="text-sm text-slate-500 font-mono mb-6 max-w-sm mx-auto">This vehicle has no recorded incidents in the surveillance grid. Be the first to file a report.</p>
                            <a href="{{ route('vehicle.rate', $plate_number) }}" class="text-cyan-400 hover:text-cyan-300 font-mono text-sm uppercase underline decoration-cyan-500/30 hover:decoration-cyan-500 transition-all">
                                Initialize New Report >>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
