<x-app-layout>
    @section('title', 'Edit Vehicle Info | MATAJALAN_OS')

    <div class="py-12 bg-slate-950 min-h-screen" x-data="editVehicleForm()">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb / Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="font-mono text-3xl font-bold text-slate-100 uppercase tracking-tight">
                        <span class="text-cyan-500">>></span> Modification Protocol
                    </h2>
                    <p class="mt-2 text-slate-400 font-mono text-sm">
                        Update sequence for Vehicle ID: <span class="text-cyan-400">{{ substr($vehicle->uuid, 0, 8) }}</span>
                    </p>
                </div>
                <a href="{{ route('vehicle.show', $vehicle->uuid) }}" class="group flex items-center space-x-2 text-slate-400 hover:text-cyan-400 transition-colors duration-300">
                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="font-mono text-sm uppercase tracking-wider">Abort Sequence</span>
                </a>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-2xl relative overflow-hidden">
                <!-- Top Decoration -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500"></div>
                
                <!-- Progress Bar -->
                <div class="h-1 w-full bg-slate-800 mt-1">
                    <div class="h-full bg-cyan-500 transition-all duration-500" :style="`width: ${(step / 3) * 100}%`"></div>
                </div>

                <div class="p-8">
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 text-cyan-500 mb-4 border border-slate-700">
                            <i data-lucide="edit-3" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-2xl font-mono font-bold text-slate-100">EDIT_ENTITY_RECORD</h2>
                        <p class="text-sm text-slate-500 font-mono mt-2">TARGET_PLATE: <span class="text-cyan-400">{{ $vehicle->plate_number }}</span></p>
                    </div>

                    <form action="{{ route('vehicle.update-request', $vehicle->uuid) }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                        @csrf
                        <!-- Hidden Plate Number for Controller Validation -->
                        <input type="hidden" name="plate_number" value="{{ $vehicle->plate_number }}">

                        <!-- Step 1: Basic Info -->
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">01 // CORE_ATTRIBUTES</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Manufacturer Searchable Dropdown -->
                                <div x-data="dropdownSearch({ 
                                    options: manufacturers, 
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
                                    @error('make') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                                </div>

                                <!-- Model Searchable Dropdown -->
                                <div x-data="dropdownSearch({ 
                                    options: [], 
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
                                            :placeholder="placeholder"
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
                                    @error('model') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Details & Verification -->
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">02 // SPECIFICATIONS & PROOF</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">MANUFACTURE_YEAR</label>
                                    <input type="number" name="year" x-model="formData.year" class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" placeholder="YYYY">
                                    @error('year') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
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
                                    @error('color') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-span-full">
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">VIN (VEHICLE_ID_NUMBER)</label>
                                    <input type="text" name="vin" x-model="formData.vin" class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm uppercase" placeholder="XXXXXXXXXXXXXXXXX">
                                    @error('vin') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- File Upload Section (Mirrored from previous implementation but styled) -->
                            <div class="border-t border-slate-800 pt-6">
                                <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">SUPPORTING_DOCUMENTATION <span class="text-red-400">*</span></label>
                                <div id="drop-zone" class="relative border border-dashed border-slate-700 bg-slate-950/50 rounded-lg p-6 text-center hover:border-cyan-500/50 transition-colors">
                                    <input id="document" type="file" name="document" 
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                        required />
                                    <div class="pointer-events-none relative z-10">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-500 mx-auto mb-2 transition-colors duration-300" id="upload-icon"></i>
                                        <p class="text-slate-300 font-mono text-sm" id="upload-text">Click to upload or drag file here</p>
                                        <p class="text-slate-500 text-xs mt-1" id="file-name">Accepted: PDF, JPG, PNG (Max 5MB)</p>
                                    </div>
                                </div>
                                @error('document') <p class="text-red-400 text-xs mt-2 font-mono">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Step 3: Review -->
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">03 // FINAL_VERIFICATION</h3>
                            
                            <div class="bg-slate-950 p-6 border border-slate-800 rounded font-mono text-sm">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Target Plate</dt>
                                        <dd class="mt-1 text-slate-200">{{ $vehicle->plate_number }}</dd>
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
                                    <div class="sm:col-span-full border-t border-slate-800 pt-4">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Documentation</dt>
                                        <dd class="mt-1 text-cyan-400" id="review-filename">No file selected</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div class="mt-6 flex items-start gap-3 p-4 bg-amber-900/10 border border-amber-500/30 rounded">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"></i>
                                <p class="text-xs text-slate-400 font-mono">By submitting this request, you certify that the modifications are accurate and supported by the attached documentation. False data injection is a punishable offense.</p>
                            </div>
                        </div>

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
                                SUBMIT_REQUEST
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data Definitions (Mirrored from Create Form)
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

        function editVehicleForm() {
            return {
                step: 1,
                formData: {
                    make: '{{ old("make", $vehicle->make) }}',
                    model: '{{ old("model", $vehicle->model) }}',
                    year: '{{ old("year", $vehicle->year) }}',
                    color: '{{ old("color", $vehicle->color) }}',
                    vin: '{{ old("vin", $vehicle->vin) }}'
                },
                manufacturers: MANUFACTURERS,
                colors: COLORS,
                
                // Helper for Colors
                getColorHex(name) {
                    const c = this.colors.find(x => x.name === name);
                    return c ? c.hex : '#333';
                },

                // Validation
                validateStep() {
                    if (this.step === 1) {
                        if (!this.formData.make || !this.formData.model) {
                            alert('Manufacturer and Model are required.');
                            return false;
                        }
                    }
                    if (this.step === 2) {
                        if (!this.formData.year || !this.formData.color) {
                            alert('Year and Color are required.');
                            return false;
                        }
                        // Check file upload (Native check)
                        const fileInput = document.getElementById('document');
                        if (!fileInput.files || !fileInput.files.length) {
                             alert('Supporting Documentation is required.');
                             return false;
                        }
                    }
                    return true;
                },
                nextStep() { if (this.validateStep()) this.step++; },
                prevStep() { this.step--; },
                submitForm(e) {
                    if (this.validateStep()) {
                        e.target.submit();
                    }
                }
            }
        }

        // Dropdown Component (Same as Create)
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdownSearch', ({ options, model, placeholder, isModel = false }) => ({
                open: false,
                search: '',
                options: options,
                
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
                            // Only reset if make changes AND we are not initializing
                            // But here we might want to keep it simple.
                            // If make changes, model likely invalid.
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
                    const make = this.formData.make;
                    if (!make || !MODELS[make]) return [];
                    const list = MODELS[make];
                    if (this.search === '') return list;
                    return list.filter(opt => 
                        opt.toLowerCase().includes(this.search.toLowerCase())
                    );
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
                    this.$el.querySelector('input[type="text"]').focus();
                }
            }));
        });
        
        // File Upload UI Logic
        const fileInput = document.getElementById('document');
        const uploadText = document.getElementById('upload-text');
        const fileName = document.getElementById('file-name');
        const reviewFilename = document.getElementById('review-filename');
        const uploadIcon = document.getElementById('upload-icon');
        const dropZone = document.getElementById('drop-zone');

        if(fileInput) {
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    uploadText.textContent = 'File Selected';
                    fileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    fileName.classList.add('text-cyan-400', 'font-bold');
                    fileName.classList.remove('text-slate-500');
                    uploadIcon.classList.add('text-cyan-500');
                    uploadIcon.classList.remove('text-slate-500');
                    dropZone.classList.add('border-cyan-500', 'bg-cyan-900/10');
                    dropZone.classList.remove('border-slate-700', 'bg-slate-950/50');
                    
                    if(reviewFilename) reviewFilename.textContent = file.name;
                }
            });

            // Drag and drop effects
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-cyan-500', 'bg-cyan-900/10');
                dropZone.classList.remove('border-slate-700', 'bg-slate-950/50');
            }

            function unhighlight(e) {
                if (!fileInput.files || !fileInput.files[0]) {
                    dropZone.classList.remove('border-cyan-500', 'bg-cyan-900/10');
                    dropZone.classList.add('border-slate-700', 'bg-slate-950/50');
                }
            }
        }
    </script>
</x-app-layout>
