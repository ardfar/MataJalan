@extends('layouts.admin')

@section('title', 'Add Vehicle Spec | MATAJALAN_OS')

@section('header', 'NEW_SPECIFICATION_ENTRY')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('admin.vehicle-specs.index') }}" class="text-slate-500 hover:text-cyan-400 font-mono text-sm flex items-center gap-2 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> BACK_TO_LIBRARY
        </a>
    </div>

    <div class="bg-slate-900 overflow-hidden shadow-lg sm:rounded-lg border border-slate-800"
         x-data="{
             type: '{{ old('type', 'car') }}',
             carBrands: {!! json_encode($carBrands) !!},
             motorcycleBrands: {!! json_encode($motorcycleBrands) !!},
             commercialBrands: {!! json_encode($commercialBrands) !!},
             carCategories: {!! json_encode($carCategories) !!},
             motorcycleCategories: {!! json_encode($motorcycleCategories) !!},
             truckCategories: {!! json_encode($truckCategories) !!},
             busCategories: {!! json_encode($busCategories) !!},
             get currentBrands() {
                 if (this.type === 'car') return this.carBrands;
                 if (this.type === 'motorcycle') return this.motorcycleBrands;
                 return this.commercialBrands;
             },
             get currentCategories() {
                 if (this.type === 'car') return this.carCategories;
                 if (this.type === 'motorcycle') return this.motorcycleCategories;
                 if (this.type === 'truck') return this.truckCategories;
                 if (this.type === 'bus') return this.busCategories;
                 return [];
             }
         }">
        <div class="p-8">
            <div class="mb-8 border-b border-slate-800 pb-6">
                <h2 class="text-xl font-mono font-bold text-slate-100 mb-2">TECHNICAL_DATA_INPUT</h2>
                <p class="text-sm text-slate-500 font-mono">
                    Register new vehicle technical specifications into the central database.
                </p>
            </div>

            <form action="{{ route('admin.vehicle-specs.store') }}" method="POST">
                @csrf

                <!-- Type Selection -->
                <div class="mb-6">
                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VEHICLE TYPE</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="type" value="car" x-model="type" class="form-radio text-cyan-600 bg-slate-950 border-slate-700 focus:ring-cyan-500">
                            <span class="ml-2 text-slate-300 font-mono text-sm">CAR</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="type" value="motorcycle" x-model="type" class="form-radio text-cyan-600 bg-slate-950 border-slate-700 focus:ring-cyan-500">
                            <span class="ml-2 text-slate-300 font-mono text-sm">MOTORCYCLE</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="type" value="truck" x-model="type" class="form-radio text-cyan-600 bg-slate-950 border-slate-700 focus:ring-cyan-500">
                            <span class="ml-2 text-slate-300 font-mono text-sm">TRUCK</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="type" value="bus" x-model="type" class="form-radio text-cyan-600 bg-slate-950 border-slate-700 focus:ring-cyan-500">
                            <span class="ml-2 text-slate-300 font-mono text-sm">BUS</span>
                        </label>
                    </div>
                    @error('type') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Brand -->
                    <div x-data="{
                        search: '{{ old('brand') }}',
                        open: false,
                        get filteredItems() {
                            if (this.search === '') return currentBrands;
                            return currentBrands.filter(i => i.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        select(item) {
                            this.search = item;
                            this.open = false;
                        }
                    }" class="relative">
                        <label for="brand" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">BRAND / MANUFACTURER</label>
                        <input type="hidden" name="brand" :value="search">
                        <div class="relative">
                            <input type="text" x-model="search" 
                                @focus="open = true" @click.away="open = false" @keydown.escape="open = false"
                                class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                                placeholder="Select or Type Brand..." autocomplete="off">
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                            </div>
                        </div>
                        
                        <div x-show="open" x-transition 
                            class="absolute z-10 mt-1 w-full bg-slate-900 border border-slate-700 max-h-60 overflow-auto shadow-lg rounded" 
                            style="display: none;">
                            <template x-for="item in filteredItems" :key="item">
                                <div @click="select(item)" class="px-4 py-2 text-sm font-mono text-slate-300 hover:bg-slate-800 hover:text-cyan-400 cursor-pointer transition-colors">
                                    <span x-text="item"></span>
                                </div>
                            </template>
                            <div x-show="filteredItems.length === 0" class="px-4 py-2 text-sm font-mono text-slate-500 italic">
                                No brands found.
                            </div>
                        </div>
                        @error('brand') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MODEL NAME</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="AVANZA / VARIO">
                        @error('model') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Variant -->
                    <div class="col-span-2">
                        <label for="variant" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VARIANT TRIM</label>
                        <input type="text" name="variant" id="variant" value="{{ old('variant') }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="1.5 G CVT TSS / 160 CBS">
                        @error('variant') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">CATEGORY</label>
                        <select name="category" id="category" required class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                            <option value="">SELECT CATEGORY</option>
                            <template x-for="cat in currentCategories" :key="cat">
                                <option :value="cat" x-text="cat" :selected="'{{ old('category') }}' === cat"></option>
                            </template>
                        </select>
                        @error('category') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Seat Capacity -->
                    <div>
                        <label for="seat_capacity" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">SEAT CAPACITY</label>
                        <input type="number" name="seat_capacity" id="seat_capacity" value="{{ old('seat_capacity') }}" required min="1" max="60"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('seat_capacity') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Cargo Capacity (Truck/Bus) -->
                    <div x-show="type === 'truck' || type === 'bus'">
                        <label for="cargo_capacity_kg" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">CARGO CAPACITY (KG)</label>
                        <input type="number" name="cargo_capacity_kg" id="cargo_capacity_kg" value="{{ old('cargo_capacity_kg') }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('cargo_capacity_kg') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- GVWR (Truck/Bus) -->
                    <div x-show="type === 'truck' || type === 'bus'">
                        <label for="gvwr_kg" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">GVWR (KG)</label>
                        <input type="number" name="gvwr_kg" id="gvwr_kg" value="{{ old('gvwr_kg') }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('gvwr_kg') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Axle Count (Truck/Bus) -->
                    <div x-show="type === 'truck' || type === 'bus'">
                        <label for="axle_count" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">AXLE COUNT</label>
                        <input type="number" name="axle_count" id="axle_count" value="{{ old('axle_count') }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('axle_count') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Engine CC -->
                    <div>
                        <label for="engine_cc" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">ENGINE CC (OPTIONAL)</label>
                        <input type="number" name="engine_cc" id="engine_cc" value="{{ old('engine_cc') }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="1496">
                        @error('engine_cc') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Battery kWh -->
                    <div>
                        <label for="battery_kwh" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">BATTERY KWH (EV ONLY)</label>
                        <input type="number" step="0.1" name="battery_kwh" id="battery_kwh" value="{{ old('battery_kwh') }}"
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                            placeholder="60.5">
                        @error('battery_kwh') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Horsepower -->
                    <div>
                        <label for="horsepower" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">HORSEPOWER (PS/HP)</label>
                        <input type="number" name="horsepower" id="horsepower" value="{{ old('horsepower') }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('horsepower') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Torque -->
                    <div>
                        <label for="torque" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">TORQUE (NM)</label>
                        <input type="number" name="torque" id="torque" value="{{ old('torque') }}" required
                            class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700">
                        @error('torque') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Transmission -->
                    <div>
                        <label for="transmission" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">TRANSMISSION</label>
                        <select name="transmission" id="transmission" required class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                            <option value="">SELECT TRANSMISSION</option>
                            <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                            <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="CVT" {{ old('transmission') == 'CVT' ? 'selected' : '' }}>CVT</option>
                            <option value="e-CVT" {{ old('transmission') == 'e-CVT' ? 'selected' : '' }}>e-CVT (Hybrid)</option>
                            <option value="DCT" {{ old('transmission') == 'DCT' ? 'selected' : '' }}>DCT</option>
                            <option value="IVT" {{ old('transmission') == 'IVT' ? 'selected' : '' }}>IVT</option>
                            <option value="Single Speed" {{ old('transmission') == 'Single Speed' ? 'selected' : '' }}>Single Speed (EV)</option>
                        </select>
                        @error('transmission') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>

                    <!-- Fuel Type -->
                    <div>
                        <label for="fuel_type" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">FUEL TYPE</label>
                        <select name="fuel_type" id="fuel_type" required class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                            <option value="">SELECT FUEL</option>
                            <option value="Bensin" {{ old('fuel_type') == 'Bensin' ? 'selected' : '' }}>Petrol (Bensin)</option>
                            <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid (HEV/PHEV)</option>
                            <option value="Mild Hybrid" {{ old('fuel_type') == 'Mild Hybrid' ? 'selected' : '' }}>Mild Hybrid</option>
                            <option value="Listrik" {{ old('fuel_type') == 'Listrik' ? 'selected' : '' }}>Electric (BEV)</option>
                        </select>
                        @error('fuel_type') <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end border-t border-slate-800 pt-6">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                        REGISTER_SPECIFICATION
                        <i data-lucide="save" class="w-4 h-4 ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
