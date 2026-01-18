<x-app-layout>
    <x-slot name="header">
        {{ __('REGISTRATION_COMPLETE') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-2xl p-8 text-center relative overflow-hidden">
                <!-- Success Decoration -->
                <div class="absolute inset-0 bg-emerald-500/5 pointer-events-none"></div>
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <i data-lucide="check-circle" class="w-16 h-16 text-emerald-500"></i>
                </div>

                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-950 border-2 border-emerald-500 mb-6 shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                        <i data-lucide="check" class="w-10 h-10 text-emerald-500"></i>
                    </div>
                    <h2 class="text-3xl font-mono font-bold text-slate-100 mb-2">ENTITY_INITIALIZED</h2>
                    <p class="text-slate-500 font-mono">Target <span class="text-emerald-400 font-bold">{{ $vehicle->plate_number }}</span> has been added to the surveillance grid.</p>
                </div>

                <!-- Vehicle Summary -->
                <div class="bg-slate-950 border border-slate-800 rounded p-6 mb-8 text-left max-w-sm mx-auto">
                    <div class="grid grid-cols-2 gap-4 text-sm font-mono">
                        <div>
                            <span class="block text-slate-600 text-[10px] uppercase">Manufacturer</span>
                            <span class="text-slate-300">{{ $vehicle->make }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-600 text-[10px] uppercase">Model</span>
                            <span class="text-slate-300">{{ $vehicle->model }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-600 text-[10px] uppercase">Year</span>
                            <span class="text-slate-300">{{ $vehicle->year }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-600 text-[10px] uppercase">Color</span>
                            <span class="text-slate-300">{{ $vehicle->color }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="border-t border-slate-800 pt-8">
                    <a href="{{ route('vehicle.show', $vehicle->uuid) }}" class="inline-flex items-center justify-center px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                        VIEW_NEWLY_ADDED_VEHICLE
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
