<x-app-layout>
    @section('title', 'Register Vehicle | MATAJALAN_OS')
    <x-slot name="header">
        {{ __('REGISTER_NEW_TARGET') }} // {{ $plate_number }}
    </x-slot>

    <div class="py-12" x-data="registrationForm()">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-2xl relative overflow-hidden">
                <!-- Progress Bar -->
                <div class="h-1 w-full bg-slate-800">
                    <div class="h-full bg-cyan-500 transition-all duration-500" :style="`width: ${(step / 3) * 100}%`"></div>
                </div>

                <div class="p-8">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 text-cyan-500 mb-4 border border-slate-700">
                            <i data-lucide="database-zap" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-2xl font-mono font-bold text-slate-100">NEW_ENTITY_REGISTRATION</h2>
                        <p class="text-sm text-slate-500 font-mono mt-2">TARGET_ID: <span class="text-cyan-400">{{ $plate_number }}</span></p>
                    </div>

                    <form action="{{ route('vehicle.store', $plate_number) }}" method="POST" @submit.prevent="submitForm">
                        @csrf
                        
                        <!-- Step 1: Basic Info -->
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">01 // CORE_ATTRIBUTES</h3>
                            
                            <!-- Type Selection -->
                            <div class="mb-6">
                                <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VEHICLE TYPE</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center cursor-pointer bg-slate-950 border border-slate-700 rounded px-4 py-2 hover:border-cyan-500 transition-colors" :class="{'border-cyan-500 bg-slate-900': formData.type === 'car'}">
                                        <input type="radio" name="type" value="car" x-model="formData.type" class="hidden">
                                        <i data-lucide="car" class="w-4 h-4 mr-2" :class="formData.type === 'car' ? 'text-cyan-400' : 'text-slate-500'"></i>
                                        <span class="font-mono text-sm" :class="formData.type === 'car' ? 'text-cyan-400' : 'text-slate-400'">CAR</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer bg-slate-950 border border-slate-700 rounded px-4 py-2 hover:border-cyan-500 transition-colors" :class="{'border-cyan-500 bg-slate-900': formData.type === 'motorcycle'}">
                                        <input type="radio" name="type" value="motorcycle" x-model="formData.type" class="hidden">
                                        <i data-lucide="bike" class="w-4 h-4 mr-2" :class="formData.type === 'motorcycle' ? 'text-cyan-400' : 'text-slate-500'"></i>
                                        <span class="font-mono text-sm" :class="formData.type === 'motorcycle' ? 'text-cyan-400' : 'text-slate-400'">MOTORCYCLE</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer bg-slate-950 border border-slate-700 rounded px-4 py-2 hover:border-cyan-500 transition-colors" :class="{'border-cyan-500 bg-slate-900': formData.type === 'truck'}">
                                        <input type="radio" name="type" value="truck" x-model="formData.type" class="hidden">
                                        <i data-lucide="truck" class="w-4 h-4 mr-2" :class="formData.type === 'truck' ? 'text-cyan-400' : 'text-slate-500'"></i>
                                        <span class="font-mono text-sm" :class="formData.type === 'truck' ? 'text-cyan-400' : 'text-slate-400'">TRUCK</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer bg-slate-950 border border-slate-700 rounded px-4 py-2 hover:border-cyan-500 transition-colors" :class="{'border-cyan-500 bg-slate-900': formData.type === 'bus'}">
                                        <input type="radio" name="type" value="bus" x-model="formData.type" class="hidden">
                                        <i data-lucide="bus" class="w-4 h-4 mr-2" :class="formData.type === 'bus' ? 'text-cyan-400' : 'text-slate-500'"></i>
                                        <span class="font-mono text-sm" :class="formData.type === 'bus' ? 'text-cyan-400' : 'text-slate-400'">BUS</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Manufacturer Searchable Dropdown -->
                                <div x-data="dropdownSearch({ 
                                    model: 'make', 
                                    placeholder: 'Select Manufacturer' 
                                })" class="relative">
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MANUFACTURER</label>
                                    <input type="hidden" name="make" x-model="formData.make">
                                    
                                    <div class="relative">
                                        <input type="text" 
                                            x-model="search" 
                                            @focus="open = true" 
                                            @click.away="open = false" 
                                            @keydown.escape="open = false"
                                            class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" 
                                            placeholder="Search manufacturer"
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
                                        <template x-for="option in getManufacturers()" :key="option">
                                            <li @click="select(option)" 
                                                class="px-3 py-2 text-sm font-mono text-slate-300 hover:bg-slate-800 hover:text-cyan-400 cursor-pointer"
                                                role="option">
                                                <span x-text="option"></span>
                                            </li>
                                        </template>
                                        <li x-show="getManufacturers().length === 0" class="px-3 py-2 text-sm font-mono text-slate-500">No matches found</li>
                                    </ul>
                                </div>

                                <!-- Model Searchable Dropdown -->
                                <div x-data="dropdownSearch({ 
                                    model: 'model', 
                                    placeholder: 'Select Model',
                                    isModel: true 
                                })" class="relative">
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MODEL_DESIGNATION</label>
                                    <input type="hidden" name="model" x-model="formData.model">
                                    
                                    <div class="relative">
                                        <input type="text" 
                                            x-model="search" 
                                            @focus="open = true" 
                                            @click.away="open = false" 
                                            @keydown.escape="open = false"
                                            class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" 
                                            placeholder="Search model"
                                            :disabled="!formData.make">
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                                        </div>
                                    </div>

                                    <ul x-show="open && formData.make" 
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
                            </div>
                        </div>

                        <!-- Step 2: Details -->
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">02 // SPECIFICATIONS</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MANUFACTURE_YEAR</label>
                                    <input type="number" x-model="formData.year" class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" placeholder="YYYY">
                                </div>
                                
                                <!-- Color Dropdown -->
                                <div x-data="{ open: false }">
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VISUAL_IDENTIFIER (COLOR)</label>
                                    <div class="relative">
                                        <button type="button" @click="open = !open" @click.away="open = false" 
                                            class="flex items-center justify-between w-full px-3 py-2 border border-slate-700 bg-slate-950 text-slate-100 font-mono text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                                            <div class="flex items-center gap-2">
                                                <template x-if="formData.color">
                                                    <span class="w-4 h-4 border border-slate-600" :style="`background-color: ${getColorHex(formData.color)}`"></span>
                                                </template>
                                                <span x-text="formData.color || 'Select Color'"></span>
                                            </div>
                                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500"></i>
                                        </button>

                                        <div x-show="open" class="absolute z-10 mt-1 w-full bg-slate-900 border border-slate-700 shadow-lg p-2 grid grid-cols-4 gap-2" style="display: none;">
                                            <template x-for="color in COLORS" :key="color.name">
                                                <div @click="formData.color = color.name; open = false" 
                                                    class="cursor-pointer hover:bg-slate-800 p-1 rounded text-center group">
                                                    <div class="w-full h-6 border border-slate-700 mb-1" :style="`background-color: ${color.hex}`"></div>
                                                    <span class="text-[10px] font-mono text-slate-400 group-hover:text-cyan-400" x-text="color.name"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <input type="hidden" name="color" x-model="formData.color">
                                </div>

                                <div class="col-span-full">
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VIN (VEHICLE_ID_NUMBER)</label>
                                    <input type="text" x-model="formData.vin" class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm uppercase" placeholder="XXXXXXXXXXXXXXXXX">
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Review -->
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">03 // VERIFICATION</h3>
                            
                            <div class="bg-slate-950 p-6 border border-slate-800 rounded font-mono text-sm">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Target Plate</dt>
                                        <dd class="mt-1 text-slate-200">{{ $plate_number }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Type</dt>
                                        <dd class="mt-1 text-slate-200 uppercase" x-text="formData.type"></dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Manufacturer</dt>
                                        <dd class="mt-1 text-slate-200" x-text="formData.make"></dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Model</dt>
                                        <dd class="mt-1 text-slate-200" x-text="formData.model"></dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Year</dt>
                                        <dd class="mt-1 text-slate-200" x-text="formData.year"></dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Color</dt>
                                        <dd class="mt-1 text-slate-200" x-text="formData.color"></dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">VIN</dt>
                                        <dd class="mt-1 text-slate-200" x-text="formData.vin"></dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div class="mt-6 flex items-start gap-3 p-4 bg-amber-900/10 border border-amber-500/30 rounded">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"></i>
                                <p class="text-xs text-slate-400 font-mono">By submitting this entry, you certify that the vehicle details are accurate to the best of your knowledge. False data injection is a punishable offense.</p>
                            </div>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="(value, key) in formData">
                            <input type="hidden" :name="key" :value="value">
                        </template>

                        <!-- Navigation -->
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-800">
                            <button type="button" x-show="step > 1" @click="prevStep" class="text-slate-500 hover:text-slate-300 font-mono text-xs uppercase tracking-wider flex items-center gap-2">
                                <i data-lucide="arrow-left" class="w-3 h-3"></i> PREV_STEP
                            </button>
                            <div class="flex-1"></div>
                            
                            <button type="button" x-show="step < 3" @click="nextStep" class="inline-flex items-center justify-center px-6 py-2 bg-slate-800 border border-slate-700 text-cyan-400 font-mono font-bold text-xs uppercase tracking-widest hover:bg-slate-700 hover:border-cyan-500 transition-all">
                                NEXT_STEP <i data-lucide="arrow-right" class="w-3 h-3 ml-2"></i>
                            </button>

                            <button type="submit" x-show="step === 3" class="inline-flex items-center justify-center px-6 py-2 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-xs uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                                INITIALIZE_RECORD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data Definitions
        const CAR_MANUFACTURERS = {!! json_encode($carBrands) !!};
        const MOTORCYCLE_MANUFACTURERS = {!! json_encode($motorcycleBrands) !!};
        const COMMERCIAL_MANUFACTURERS = {!! json_encode($commercialBrands) !!};

        const MODELS = {
            // CARS
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
            'DFSK': ['Glory 560', 'Glory i-Auto', 'Gelora'],

            // MOTORCYCLES
            'Honda_Motor': ['BeAT', 'Vario', 'PCX', 'ADV', 'Scoopy', 'Genio', 'CBR150R', 'CBR250RR', 'CRF150L', 'Supra GTR', 'Revo'],
            'Yamaha': ['NMAX', 'Aerox', 'XMAX', 'Fazzio', 'Grand Filano', 'Mio M3', 'R15', 'R25', 'MT-15', 'XSR 155', 'WR155R'],
            'Suzuki_Motor': ['Satria F150', 'GSX-R150', 'Address', 'Nex II', 'Burgman Street', 'V-Strom'],
            'Kawasaki': ['Ninja ZX-25R', 'Ninja 250', 'KLX 150', 'KLX 250', 'W175', 'Versys-X'],
            'Vespa': ['Sprint', 'Primavera', 'LX', 'GTS', 'Elettrica'],
            'TVS': ['Callisto', 'Ntorq', 'Ronin'],
            'Alva': ['One', 'Cervo'],
            'United': ['T1800', 'TX3000']
        };

        // Mapping for shared brand names (if any)
        // Since we have separate lists, we can handle duplicates or just check both.
        // But keys in MODELS must match the selected manufacturer.
        // Honda exists in both. I should differentiate or merge.
        // In the dropdown, the value is "Honda".
        // I will merge them or split. 
        // Better: The dropdown sends "Honda". MODELS['Honda'] currently returns Cars.
        // I need to dynamically switch MODELS based on type.
        
        const CAR_MODELS = {
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

        const MOTORCYCLE_MODELS = {
            'Honda': ['BeAT', 'Vario', 'PCX', 'ADV', 'Scoopy', 'Genio', 'CBR150R', 'CBR250RR', 'CRF150L', 'Supra GTR', 'Revo'],
            'Yamaha': ['NMAX', 'Aerox', 'XMAX', 'Fazzio', 'Grand Filano', 'Mio M3', 'R15', 'R25', 'MT-15', 'XSR 155', 'WR155R'],
            'Suzuki': ['Satria F150', 'GSX-R150', 'Address', 'Nex II', 'Burgman Street', 'V-Strom'],
            'Kawasaki': ['Ninja ZX-25R', 'Ninja 250', 'KLX 150', 'KLX 250', 'W175', 'Versys-X'],
            'Vespa': ['Sprint', 'Primavera', 'LX', 'GTS', 'Elettrica'],
            'TVS': ['Callisto', 'Ntorq', 'Ronin'],
            'Alva': ['One', 'Cervo'],
            'United': ['T1800', 'TX3000']
        };

        const COMMERCIAL_MODELS = {
            'Mitsubishi Fuso': ['Canter', 'Fighter X', 'Canter Bus'],
            'Hino': ['300 Series', '500 Series', 'R 260', 'RM 280'],
            'Isuzu': ['Elf', 'Giga', 'Elf Microbus'],
            'UD Trucks': ['Quester'],
            'Mercedes-Benz': ['Axor', 'OH 1626', 'OC 500 RF'],
            'Scania': ['P360', 'K410IB'],
            'Volvo': ['FM', 'FH', 'B11R', 'B8R'], // Added common Volvo models
            'FAW': ['J6P', 'JH6'], // Added common FAW models
            'Tata': ['Prima', 'Ultra'], // Added common Tata models
            'MAN': ['TGS', 'TGX', 'R37'] // Added common MAN models
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
                step: 1,
                colorOpen: false,
                formData: {
                    type: 'car',
                    make: '',
                    model: '',
                    year: '',
                    color: '',
                    vin: ''
                },
                
                init() {
                    const saved = localStorage.getItem('vehicle_reg_{{ $plate_number }}');
                    if (saved) {
                        this.formData = JSON.parse(saved);
                        // Ensure type is set if loading from old data
                        if (!this.formData.type) this.formData.type = 'car';
                    }
                    this.$watch('formData', (value) => {
                        localStorage.setItem('vehicle_reg_{{ $plate_number }}', JSON.stringify(value));
                    }, { deep: true });
                    
                    // Reset make/model if type changes
                    this.$watch('formData.type', (val) => {
                        // Optional: clear make/model on type switch to avoid mismatch
                        // this.formData.make = '';
                        // this.formData.model = '';
                    });
                },

                // --- Helper for Colors ---
                getColorHex(name) {
                    const c = COLORS.find(x => x.name === name);
                    return c ? c.hex : '#333';
                },

                // --- Validation ---
                validateStep() {
                    if (this.step === 1) {
                        if (!this.formData.make || !this.formData.model) {
                            alert('Manufacturer and Model are required.');
                            return false;
                        }
                    }
                    if (this.step === 2) {
                        if (!this.formData.year || !this.formData.color) {
                            alert('All fields are required.');
                            return false;
                        }
                    }
                    return true;
                },
                nextStep() { if (this.validateStep()) this.step++; },
                prevStep() { this.step--; },
                submitForm(e) {
                    if (this.validateStep()) {
                        localStorage.removeItem('vehicle_reg_{{ $plate_number }}');
                        e.target.submit();
                    }
                }
            }
        }

        // --- Reusable Dropdown Search Component ---
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdownSearch', ({ model, placeholder, isModel = false }) => ({
                open: false,
                search: '',
                
                init() {
                    // Sync initial value
                    if (this.formData[model]) {
                        this.search = this.formData[model];
                    }
                    // Watch for changes in parent data
                    this.$watch(`formData.${model}`, (val) => {
                        if (val !== this.search) this.search = val;
                    });
                    
                    // Special logic for Model: Reset if Make changes
                    if (isModel) {
                        this.$watch('formData.make', () => {
                            this.search = '';
                            this.formData.model = '';
                        });
                    }
                },

                getManufacturers() {
                     if (this.formData.type === 'car') return CAR_MANUFACTURERS;
                     if (this.formData.type === 'motorcycle') return MOTORCYCLE_MANUFACTURERS;
                     return COMMERCIAL_MANUFACTURERS;
                },

                getModels() {
                    if (!isModel) return [];
                    const make = this.formData.make;
                    let list = [];
                    
                    if (this.formData.type === 'car') {
                        if (make && CAR_MODELS[make]) list = CAR_MODELS[make];
                    } else if (this.formData.type === 'motorcycle') {
                        if (make && MOTORCYCLE_MODELS[make]) list = MOTORCYCLE_MODELS[make];
                    } else {
                        // Commercial (Truck/Bus)
                        if (make && COMMERCIAL_MODELS[make]) list = COMMERCIAL_MODELS[make];
                    }
                    
                    if (this.search === '') return list;
                    return list.filter(opt => 
                        opt.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                
                // Helper to switch context
                get filteredOptions() {
                     // Not used, we use getManufacturers or getModels directly in template
                     return [];
                },

                select(option) {
                    this.formData[model] = option;
                    this.search = option;
                    this.open = false;
                },

                enableManualEntry() {
                    this.search = '';
                    this.formData[model] = '';
                    this.open = false;
                    // Focus input to allow typing custom
                    this.$el.querySelector('input[type="text"]').focus();
                }
            }));
        });
    </script>
</x-app-layout>
