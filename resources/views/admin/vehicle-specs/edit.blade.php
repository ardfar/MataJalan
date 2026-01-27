@extends('layouts.admin')

@section('title', 'Edit Vehicle Spec | MATAJALAN_OS')

@section('header', 'EDIT_SPECIFICATION')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('admin.vehicle-specs.index') }}" class="text-slate-500 hover:text-cyan-400 font-mono text-sm flex items-center gap-2 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> BACK_TO_LIBRARY
        </a>
    </div>

    <div class="bg-slate-900 overflow-hidden shadow-lg sm:rounded-lg border border-slate-800">
        <div class="p-8">
            <div class="mb-8 border-b border-slate-800 pb-6">
                <h2 class="text-xl font-mono font-bold text-slate-100 mb-2">UPDATE_TECHNICAL_DATA</h2>
                <p class="text-sm text-slate-500 font-mono">
                    Update existing vehicle technical specifications.
                </p>
            </div>

            <form action="{{ route('admin.vehicle-specs.update', $vehicleSpec) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">BRAND / MANUFACTURER</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand', $vehicleSpec->brand) }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="TOYOTA">
                        @error('brand') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MODEL NAME</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $vehicleSpec->model) }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="AVANZA">
                        @error('model') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Variant -->
                    <div class="col-span-2">
                        <label for="variant" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VARIANT TRIM</label>
                        <input type="text" name="variant" id="variant" value="{{ old('variant', $vehicleSpec->variant) }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="1.5 G CVT TSS">
                        @error('variant') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">CATEGORY</label>
                        <select name="category" id="category" required class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                            <option value="">SELECT CATEGORY</option>
                            @foreach(['MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $vehicleSpec->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Seat Capacity -->
                    <div>
                        <label for="seat_capacity" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">SEAT CAPACITY</label>
                        <input type="number" name="seat_capacity" id="seat_capacity" value="{{ old('seat_capacity', $vehicleSpec->seat_capacity) }}" required min="1" max="60"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('seat_capacity') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Engine CC -->
                    <div>
                        <label for="engine_cc" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">ENGINE CC (OPTIONAL)</label>
                        <input type="number" name="engine_cc" id="engine_cc" value="{{ old('engine_cc', $vehicleSpec->engine_cc) }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="1496">
                        @error('engine_cc') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Battery kWh -->
                    <div>
                        <label for="battery_kwh" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">BATTERY KWH (EV ONLY)</label>
                        <input type="number" step="0.1" name="battery_kwh" id="battery_kwh" value="{{ old('battery_kwh', $vehicleSpec->battery_kwh) }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="60.5">
                        @error('battery_kwh') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Horsepower -->
                    <div>
                        <label for="horsepower" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">HORSEPOWER (PS/HP)</label>
                        <input type="number" name="horsepower" id="horsepower" value="{{ old('horsepower', $vehicleSpec->horsepower) }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('horsepower') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Torque -->
                    <div>
                        <label for="torque" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">TORQUE (NM)</label>
                        <input type="number" name="torque" id="torque" value="{{ old('torque', $vehicleSpec->torque) }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('torque') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Transmission -->
                    <div>
                        <label for="transmission" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">TRANSMISSION</label>
                        <select name="transmission" id="transmission" required class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                            <option value="">SELECT TRANSMISSION</option>
                            @foreach(['Manual', 'Automatic', 'CVT', 'e-CVT', 'DCT', 'IVT', 'Single Speed'] as $trans)
                                <option value="{{ $trans }}" {{ old('transmission', $vehicleSpec->transmission) == $trans ? 'selected' : '' }}>{{ $trans }}</option>
                            @endforeach
                        </select>
                        @error('transmission') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Fuel Type -->
                    <div>
                        <label for="fuel_type" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">FUEL TYPE</label>
                        <select name="fuel_type" id="fuel_type" required class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                            <option value="">SELECT FUEL</option>
                            @foreach(['Bensin', 'Diesel', 'Hybrid', 'Mild Hybrid', 'Listrik'] as $fuel)
                                <option value="{{ $fuel }}" {{ old('fuel_type', $vehicleSpec->fuel_type) == $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                            @endforeach
                        </select>
                        @error('fuel_type') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end border-t border-slate-800 pt-6">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                        UPDATE_SPECIFICATION
                        <i data-lucide="save" class="w-4 h-4 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
