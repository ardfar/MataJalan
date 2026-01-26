@extends('layouts.app')

@section('title', 'Add My Vehicle | MATAJALAN_OS')

@section('header', 'ADD_VEHICLE_PROTOCOL')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('my-vehicles.index') }}" class="text-slate-500 hover:text-cyan-400 font-mono text-sm flex items-center gap-2 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> BACK_TO_FLEET
            </a>
        </div>

        <div class="bg-slate-900 overflow-hidden shadow-lg sm:rounded-lg border border-slate-800">
            <div class="p-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 text-cyan-500 mb-4 border border-slate-700">
                        <i data-lucide="search" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-mono font-bold text-slate-100">VEHICLE_IDENTIFICATION</h2>
                    <p class="text-sm text-slate-500 font-mono mt-2">Enter the license plate number to proceed.</p>
                </div>

                @if(session('info'))
                    <div class="mb-6 bg-slate-800 border border-cyan-500/30 text-cyan-400 px-4 py-3 rounded relative font-mono text-sm" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @endif

                <form action="{{ route('my-vehicles.check') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="plate_number" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">LICENSE_PLATE</label>
                        <div class="relative">
                            <input type="text" name="plate_number" id="plate_number" 
                                class="block w-full pl-4 pr-12 py-3 border border-slate-700 rounded bg-slate-950 text-slate-100 placeholder-slate-600 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-lg uppercase tracking-widest"
                                placeholder="B 1234 XXX" required autofocus>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i data-lucide="car" class="w-5 h-5 text-slate-600"></i>
                            </div>
                        </div>
                        @error('plate_number')
                            <p class="mt-2 text-sm text-red-400 font-mono">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                            SEARCH_VEHICLE
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
