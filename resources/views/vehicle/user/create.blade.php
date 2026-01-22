<x-app-layout>
    @section('title', 'Register Vehicle User | MATAJALAN_OS')

    <div class="py-12 bg-slate-950 min-h-screen" x-data="vehicleUserForm()">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb / Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="font-mono text-3xl font-bold text-slate-100 uppercase tracking-tight">
                        <span class="text-cyan-500">>></span> User Registration Protocol
                    </h2>
                    <p class="mt-2 text-slate-400 font-mono text-sm">
                        Association sequence for Vehicle ID: <span class="text-cyan-400">{{ substr($vehicle->uuid, 0, 8) }}</span>
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
                            <i data-lucide="user-plus" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-2xl font-mono font-bold text-slate-100">INITIATE_USER_LINK</h2>
                        <p class="text-sm text-slate-500 font-mono mt-2">TARGET_PLATE: <span class="text-cyan-400">{{ $vehicle->plate_number }}</span></p>
                    </div>

                    <form action="{{ route('vehicle.user.store', $vehicle->uuid) }}" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
                        @csrf

                        <!-- Step 1: Identity & Role -->
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">01 // IDENTITY_PROTOCOL</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">OPERATIONAL_ROLE</label>
                                    <select name="role_type" x-model="formData.role_type" class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm">
                                        <option value="" disabled>SELECT ROLE TYPE</option>
                                        <option value="personal">PERSONAL (Private Owner)</option>
                                        <option value="corporate">CORPORATE (Company Vehicle)</option>
                                        <option value="taxi">TAXI (Public Transport)</option>
                                        <option value="e_hauling">E-HAULING (Online Taxi/Delivery)</option>
                                        <option value="government">GOVERNMENT (Official Use)</option>
                                    </select>
                                    @error('role_type') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">NAME_OF_THE_DRIVER</label>
                                    <input type="text" name="driver_name" x-model="formData.driver_name" class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm" placeholder="Enter Driver Name">
                                    @error('driver_name') <p class="text-red-400 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Verification -->
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">02 // VERIFICATION_DATA</h3>
                            
                            <div class="border-t border-slate-800 pt-6">
                                <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">PROOF_OF_ASSOCIATION <span class="text-red-400">*</span></label>
                                <p class="text-xs text-slate-500 mb-4 font-mono">Upload ownership document, company assignment letter, or valid STNK.</p>
                                
                                <div id="drop-zone" class="relative border border-dashed border-slate-700 bg-slate-950/50 rounded-lg p-6 text-center hover:border-cyan-500/50 transition-colors">
                                    <input id="evidence" type="file" name="evidence" 
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                        required />
                                    <div class="pointer-events-none relative z-10">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-500 mx-auto mb-2 transition-colors duration-300" id="upload-icon"></i>
                                        <p class="text-slate-300 font-mono text-sm" id="upload-text">Click to upload or drag file here</p>
                                        <p class="text-slate-500 text-xs mt-1" id="file-name">Accepted: PDF, JPG, PNG (Max 5MB)</p>
                                    </div>
                                </div>
                                @error('evidence') <p class="text-red-400 text-xs mt-2 font-mono">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Step 3: Review -->
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-lg font-mono font-bold text-slate-300 mb-6 border-b border-slate-800 pb-2">03 // FINAL_AUTHORIZATION</h3>
                            
                            <div class="bg-slate-950 p-6 border border-slate-800 rounded font-mono text-sm">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Target Plate</dt>
                                        <dd class="mt-1 text-slate-200">{{ $vehicle->plate_number }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">User Role</dt>
                                        <dd class="mt-1 text-slate-200 uppercase" x-text="formData.role_type"></dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Driver Name</dt>
                                        <dd class="mt-1 text-slate-200" x-text="formData.driver_name"></dd>
                                    </div>
                                    <div class="sm:col-span-full border-t border-slate-800 pt-4">
                                        <dt class="text-xs font-medium text-slate-500 uppercase">Evidence Document</dt>
                                        <dd class="mt-1 text-cyan-400" id="review-filename">No file selected</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div class="mt-6 flex items-start gap-3 p-4 bg-amber-900/10 border border-amber-500/30 rounded">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"></i>
                                <p class="text-xs text-slate-400 font-mono">I hereby declare that the information provided is true and correct. I understand that submitting false evidence will result in immediate account termination.</p>
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
        function vehicleUserForm() {
            return {
                step: 1,
                formData: {
                    role_type: '{{ old("role_type") }}',
                    driver_name: '{{ old("driver_name") }}',
                },
                
                // Validation
                validateStep() {
                    if (this.step === 1) {
                        if (!this.formData.role_type) {
                            alert('Please select a Role Type.');
                            return false;
                        }
                        if (!this.formData.driver_name) {
                            alert('Driver Name is required.');
                            return false;
                        }
                    }
                    if (this.step === 2) {
                        // Check file upload (Native check)
                        const fileInput = document.getElementById('evidence');
                        if (!fileInput.files || !fileInput.files.length) {
                             alert('Proof of Association is required.');
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

        // File Upload UI Logic
        const fileInput = document.getElementById('evidence');
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
