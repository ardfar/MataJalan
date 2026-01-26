@extends('layouts.app')

@section('title', 'Register New Vehicle | MATAJALAN_OS')

@section('header', 'NEW_ASSET_REGISTRATION')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('my-vehicles.create') }}" class="text-slate-500 hover:text-cyan-400 font-mono text-sm flex items-center gap-2 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> BACK_TO_SEARCH
            </a>
        </div>

        <div class="bg-slate-900 overflow-hidden shadow-lg sm:rounded-lg border border-slate-800">
            <div class="p-8">
                <div class="mb-8 border-b border-slate-800 pb-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-amber-900/20 rounded border border-amber-500/50 flex items-center justify-center">
                            <i data-lucide="alert-circle" class="w-6 h-6 text-amber-500"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-mono font-bold text-slate-100">VEHICLE_NOT_FOUND</h2>
                            <p class="text-sm text-slate-500 font-mono">Plate <span class="text-amber-400">{{ $plate }}</span> is not in our database.</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 font-mono leading-relaxed">
                        Please register the vehicle details below to proceed with driver association.
                        All fields are required for asset tracking.
                    </p>
                </div>

                <form action="{{ route('my-vehicles.store') }}" method="POST" x-data="{
                    formData: {
                        make: '{{ old('make') ?? '' }}',
                        model: '{{ old('model') ?? '' }}',
                        year: '{{ old('year') ?? '' }}',
                        color: '{{ old('color') ?? '' }}',
                        vin: '{{ old('vin') ?? '' }}'
                    },
                    manufacturers: [
                        'Toyota', 'Honda', 'Daihatsu', 'Mitsubishi', 'Suzuki', 'Hyundai', 
                        'Wuling', 'Nissan', 'Mazda', 'Kia', 'BMW', 'Mercedes-Benz', 
                        'Lexus', 'Isuzu', 'Chery', 'DFSK'
                    ],
                    colors: [
                        { name: 'Black', hex: '#000000' },
                        { name: 'White', hex: '#FFFFFF' },
                        { name: 'Silver', hex: '#C0C0C0' },
                        { name: 'Gray', hex: '#808080' },
                        { name: 'Red', hex: '#FF0000' },
                        { name: 'Blue', hex: '#0000FF' },
                        { name: 'Brown', hex: '#A52A2A' },
                        { name: 'Green', hex: '#008000' },
                        { name: 'Yellow', hex: '#FFFF00' },
                        { name: 'Orange', hex: '#FFA500' },
                        { name: 'Purple', hex: '#800080' },
                        { name: 'Gold', hex: '#FFD700' }
                    ],
                    getColorHex(name) {
                        const c = this.colors.find(x => x.name === name);
                        return c ? c.hex : '#333';
                    }
                }">
                    @csrf
                    <input type="hidden" name="plate_number" value="{{ $plate }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Plate (Read Only) -->
                        <div class="col-span-2">
                            <label class="block font-mono font-bold text-xs text-slate-500 uppercase tracking-wide mb-2">TARGET_PLATE</label>
                            <input type="text" value="{{ $plate }}" disabled class="block w-full px-4 py-3 border border-slate-700 rounded bg-slate-950/50 text-slate-400 font-mono cursor-not-allowed">
                        </div>

                        <!-- Make -->
                        <div x-data="dropdownSearch({ 
                            options: manufacturers, 
                            model: 'make', 
                            placeholder: 'SELECT MANUFACTURER',
                            form: formData 
                        })" class="relative">
                            <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MANUFACTURER</label>
                            <input type="hidden" name="make" x-model="form.make">
                            
                            <div class="relative">
                                <input type="text" 
                                    x-model="search" 
                                    @focus="open = true" 
                                    @click.away="open = false" 
                                    @keydown.escape="open = false"
                                    class="block w-full px-4 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700" 
                                    :placeholder="placeholder"
                                    aria-haspopup="listbox"
                                    :aria-expanded="open">
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                                </div>
                            </div>

                            <ul x-show="open" 
                                class="absolute z-10 mt-1 w-full bg-slate-900 border border-slate-700 max-h-60 overflow-auto shadow-lg" 
                                role="listbox"
                                style="display: none;">
                                <template x-for="option in filteredOptions" :key="option">
                                    <li @click="select(option)" 
                                        class="px-3 py-2 text-sm font-mono text-slate-300 hover:bg-slate-800 hover:text-cyan-400 cursor-pointer"
                                        role="option">
                                        <span x-text="option"></span>
                                    </li>
                                </template>
                                <li x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm font-mono text-slate-500">No matches found</li>
                            </ul>
                        </div>

                        <!-- Model -->
                        <div x-data="dropdownSearch({ 
                            options: [], 
                            model: 'model', 
                            placeholder: 'SELECT MODEL',
                            isModel: true,
                            form: formData 
                        })" class="relative">
                            <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MODEL</label>
                            <input type="hidden" name="model" x-model="form.model">
                            
                            <div class="relative">
                                <input type="text" 
                                    x-model="search" 
                                    @focus="open = true" 
                                    @click.away="open = false" 
                                    @keydown.escape="open = false"
                                    class="block w-full px-4 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700" 
                                    :placeholder="placeholder"
                                    :disabled="!form.make">
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                                </div>
                            </div>

                            <ul x-show="open && form.make" 
                                class="absolute z-10 mt-1 w-full bg-slate-900 border border-slate-700 max-h-60 overflow-auto shadow-lg" 
                                style="display: none;">
                                <template x-for="option in getModels()" :key="option">
                                    <li @click="select(option)" 
                                        class="px-3 py-2 text-sm font-mono text-slate-300 hover:bg-slate-800 hover:text-cyan-400 cursor-pointer">
                                        <span x-text="option"></span>
                                    </li>
                                </template>
                                <li @click="enableManualEntry()" class="px-3 py-2 text-sm font-mono text-cyan-500 hover:bg-slate-800 cursor-pointer border-t border-slate-800 italic">
                                    + Other (Manual Entry)
                                </li>
                            </ul>
                        </div>

                        <!-- Year -->
                        <div>
                            <label for="year" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MANUFACTURE_YEAR</label>
                            <input type="number" name="year" id="year" x-model="formData.year" required min="1900" max="{{ date('Y')+1 }}"
                                class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700"
                                placeholder="2022">
                            @error('year')
                                <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Color -->
                        <div x-data="{ open: false }">
                            <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">COLOR</label>
                            <div class="relative">
                                <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="flex items-center justify-between w-full px-4 py-2 border border-slate-700 bg-slate-950 text-slate-100 font-mono text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 rounded">
                                    <div class="flex items-center gap-2">
                                        <template x-if="formData.color">
                                            <span class="w-4 h-4 border border-slate-600" :style="`background-color: ${getColorHex(formData.color)}`"></span>
                                        </template>
                                        <span x-text="formData.color || 'SELECT COLOR'" :class="{'text-slate-700': !formData.color}"></span>
                                    </div>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                                </button>

                                <div x-show="open" class="absolute z-10 mt-1 w-full bg-slate-900 border border-slate-700 shadow-lg p-2 grid grid-cols-4 gap-2" style="display: none;">
                                    <template x-for="color in colors" :key="color.name">
                                        <div @click="formData.color = color.name; open = false" 
                                            class="cursor-pointer hover:bg-slate-800 p-1 rounded text-center group">
                                            <div class="w-full h-6 border border-slate-700 mb-1" :style="`background-color: ${color.hex}`"></div>
                                            <span class="text-[10px] font-mono text-slate-400 group-hover:text-cyan-400" x-text="color.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <input type="hidden" name="color" x-model="formData.color">
                            @error('color')
                                <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- VIN -->
                        <div class="col-span-2">
                            <label for="vin" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VIN / CHASSIS_NUMBER (OPTIONAL)</label>
                            <input type="text" name="vin" id="vin" x-model="formData.vin"
                                class="block w-full px-4 py-2 border border-slate-700 rounded bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm placeholder-slate-700 uppercase"
                                placeholder="MHF...">
                            @error('vin')
                                <p class="mt-1 text-xs text-red-400 font-mono">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t border-slate-800 pt-6">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                            REGISTER_AND_CONTINUE
                            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script>
        // Data Definitions
        const MANUFACTURERS = [
            'Toyota', 'Honda', 'Daihatsu', 'Mitsubishi', 'Suzuki', 'Hyundai', 
            'Wuling', 'Nissan', 'Mazda', 'Kia', 'BMW', 'Mercedes-Benz', 
            'Lexus', 'Isuzu', 'Chery', 'DFSK'
        ];

        const MODELS = {
            'Toyota': ['Avanza', 'Veloz', 'Innova Zenix', 'Fortuner', 'Rush', 'Calya', 'Agya', 'Raize', 'Yaris Cross', 'Alphard', 'Voxy', 'Camry', 'Corolla Altis', 'Hilux', 'Hiace'],
            'Honda': ['Brio', 'HR-V', 'BR-V', 'WR-V', 'CR-V', 'City Hatchback', 'Civic', 'Accord', 'Mobilio', 'Odyssey'],
            'Daihatsu': ['Sigra', 'Xenia', 'Terios', 'Ayla', 'Rocky', 'Gran Max', 'Luxio', 'Sirion'],
            'Mitsubishi': ['Xpander', 'Xpander Cross', 'Pajero Sport', 'Xforce', 'Triton', 'L300'],
            'Suzuki': ['Ertiga', 'XL7', 'Jimny', 'Baleno', 'S-Presso', 'Ignis', 'Grand Vitara', 'Carry'],
            'Hyundai': ['Stargazer', 'Creta', 'Ioniq 5', 'Palisade', 'Santa Fe', 'Staria', 'Ioniq 6'],
            'Wuling': ['Confero', 'Almaz', 'Air EV', 'Binguo', 'Cortez', 'Formo'],
            'Isuzu': ['Panther', 'Mu-X', 'D-Max', 'Traga'],
            'Nissan': ['Livina', 'Magnite', 'Kicks', 'Serena', 'Terra', 'X-Trail'],
            'Mazda': ['CX-5', 'CX-3', 'Mazda2', 'Mazda3', 'CX-30', 'CX-60'],
            'Kia': ['Sonet', 'Seltos', 'Carens', 'Carnival', 'EV6', 'EV9'],
            'BMW': ['3 Series', '5 Series', 'X1', 'X3', 'X5', 'iX'],
            'Mercedes-Benz': ['C-Class', 'E-Class', 'GLC', 'GLE', 'A-Class', 'S-Class'],
            'Chery': ['Tiggo 7 Pro', 'Tiggo 8 Pro', 'Omoda 5'],
            'DFSK': ['Glory 560', 'Glory i-Auto', 'Gelora']
        };

        const COLORS = [
            { name: 'Black', hex: '#000000' },
            { name: 'White', hex: '#FFFFFF' },
            { name: 'Silver', hex: '#C0C0C0' },
            { name: 'Gray', hex: '#808080' },
            { name: 'Red', hex: '#FF0000' },
            { name: 'Blue', hex: '#0000FF' },
            { name: 'Brown', hex: '#A52A2A' },
            { name: 'Green', hex: '#008000' },
            { name: 'Yellow', hex: '#FFFF00' },
            { name: 'Orange', hex: '#FFA500' },
            { name: 'Purple', hex: '#800080' },
            { name: 'Gold', hex: '#FFD700' }
        ];

        function registrationForm() {
            return {
                formData: {
                    make: '{{ old('make') }}',
                    model: '{{ old('model') }}',
                    year: '{{ old('year') }}',
                    color: '{{ old('color') }}',
                    vin: '{{ old('vin') }}'
                },
                manufacturers: MANUFACTURERS,
                colors: COLORS,
                
                // --- Helper for Colors ---
                getColorHex(name) {
                    const c = this.colors.find(x => x.name === name);
                    return c ? c.hex : '#333';
                }
            }
        }

        // --- Reusable Dropdown Search Component ---
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdownSearch', ({ options, model, placeholder, isModel = false, form }) => ({
                open: false,
                search: '',
                options: options,
                form: form,
                models: {
                    'Toyota': ['Avanza', 'Veloz', 'Innova Zenix', 'Fortuner', 'Rush', 'Calya', 'Agya', 'Raize', 'Yaris Cross', 'Alphard', 'Voxy', 'Camry', 'Corolla Altis', 'Hilux', 'Hiace'],
                    'Honda': ['Brio', 'HR-V', 'BR-V', 'WR-V', 'CR-V', 'City Hatchback', 'Civic', 'Accord', 'Mobilio', 'Odyssey'],
                    'Daihatsu': ['Sigra', 'Xenia', 'Terios', 'Ayla', 'Rocky', 'Gran Max', 'Luxio', 'Sirion'],
                    'Mitsubishi': ['Xpander', 'Xpander Cross', 'Pajero Sport', 'Xforce', 'Triton', 'L300'],
                    'Suzuki': ['Ertiga', 'XL7', 'Jimny', 'Baleno', 'S-Presso', 'Ignis', 'Grand Vitara', 'Carry'],
                    'Hyundai': ['Stargazer', 'Creta', 'Ioniq 5', 'Palisade', 'Santa Fe', 'Staria', 'Ioniq 6'],
                    'Wuling': ['Confero', 'Almaz', 'Air EV', 'Binguo', 'Cortez', 'Formo'],
                    'Isuzu': ['Panther', 'Mu-X', 'D-Max', 'Traga'],
                    'Nissan': ['Livina', 'Magnite', 'Kicks', 'Serena', 'Terra', 'X-Trail'],
                    'Mazda': ['CX-5', 'CX-3', 'Mazda2', 'Mazda3', 'CX-30', 'CX-60'],
                    'Kia': ['Sonet', 'Seltos', 'Carens', 'Carnival', 'EV6', 'EV9'],
                    'BMW': ['3 Series', '5 Series', 'X1', 'X3', 'X5', 'iX'],
                    'Mercedes-Benz': ['C-Class', 'E-Class', 'GLC', 'GLE', 'A-Class', 'S-Class'],
                    'Chery': ['Tiggo 7 Pro', 'Tiggo 8 Pro', 'Omoda 5'],
                    'DFSK': ['Glory 560', 'Glory i-Auto', 'Gelora']
                },
                
                init() {
                    // Sync initial value
                    if (this.form[model]) {
                        this.search = this.form[model];
                    }
                    // Watch for changes in parent data
                    this.$watch('form.' + model, (val) => {
                        if (val !== this.search) this.search = val;
                    });
                    
                    // Special logic for Model: Reset if Make changes
                    if (isModel) {
                        this.$watch('form.make', () => {
                            this.search = '';
                            this.form.model = '';
                        });
                    }
                },

                get filteredOptions() {
                    if (this.search === '') return this.options;
                    return this.options.filter(opt => 
                        opt.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                getModels() {
                    if (!isModel) return [];
                    const make = this.form.make;
                    if (!make || !this.models[make]) return [];
                    const list = this.models[make];
                    if (this.search === '') return list;
                    return list.filter(opt => 
                        opt.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                select(option) {
                    this.form[model] = option;
                    this.search = option;
                    this.open = false;
                },

                enableManualEntry() {
                    this.search = '';
                    this.form[model] = '';
                    this.open = false;
                    // Focus input to allow typing custom
                    this.$el.querySelector('input[type="text"]').focus();
                }
            }));
        });
    </script>
@endsection
