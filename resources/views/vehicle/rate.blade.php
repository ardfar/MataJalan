<x-app-layout>
    <x-slot name="header">
        {{ __('SUBMIT_INCIDENT_REPORT') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-2xl p-8 relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <i data-lucide="siren" class="w-16 h-16 text-cyan-500"></i>
                </div>

                <div class="flex items-center justify-between mb-8 border-b border-slate-800 pb-4">
                    <div>
                        <h1 class="text-2xl font-mono font-bold text-slate-100 mb-1">TARGET: {{ $plate_number }}</h1>
                        <p class="text-xs text-slate-500 font-mono">FILE NEW SURVEILLANCE ENTRY</p>
                    </div>
                    <div class="text-xs font-mono text-cyan-500 border border-cyan-500/30 bg-cyan-900/10 px-2 py-1 rounded">
                        STATUS: ACTIVE
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-900/20 border border-red-500/50 text-red-400 px-4 py-3 rounded mb-6 font-mono text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('vehicle.storeRating', $plate_number) }}" method="POST">
                    @csrf
                    
                    <!-- Rating Stars -->
                    <div class="mb-8">
                        <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-3">THREAT_LEVEL_ASSESSMENT</label>
                        <div class="flex gap-4 p-4 bg-slate-950 border border-slate-800 rounded-lg justify-center">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer group text-center">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                    <i data-lucide="star" class="w-8 h-8 text-slate-700 fill-slate-900 peer-checked:text-amber-500 peer-checked:fill-amber-500 group-hover:text-amber-400 transition-colors mb-1"></i>
                                    <span class="block text-[10px] font-mono text-slate-600 peer-checked:text-amber-500">{{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                        <p class="text-[10px] text-slate-500 mt-2 font-mono text-center">1 = CRITICAL THREAT ... 5 = SAFE/COMPLIANT</p>
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">INCIDENT_DESCRIPTION</label>
                        <textarea name="comment" id="comment" rows="4" 
                            class="block w-full p-3 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm transition-all"
                            placeholder="Provide detailed observation of the event..."></textarea>
                    </div>

                    <!-- Tags -->
                    <div class="mb-8">
                        <label for="tags" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">BEHAVIORAL_TAGS (COMMA_SEPARATED)</label>
                        <input type="text" name="tags" id="tags" 
                            class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm transition-all"
                            placeholder="e.g. SPEEDING, AGGRESSIVE, RED_LIGHT_VIOLATION">
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-800">
                        <a href="{{ route('vehicle.show', $plate_number) }}" class="text-slate-500 hover:text-slate-300 font-mono text-xs uppercase tracking-wider">
                            < CANCEL_ENTRY
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-xs uppercase tracking-widest hover:bg-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition ease-in-out duration-150">
                            SUBMIT_TO_ARCHIVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
