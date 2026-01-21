<x-app-layout>
    @section('title', 'Submit Report | MATAJALAN_OS')
    <x-slot name="header">
        {{ __('SUBMIT_INCIDENT_REPORT') }}
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        .leaflet-container {
            background: #0f172a; /* Match slate-900 */
        }
        .custom-map-popup .leaflet-popup-content-wrapper {
            background: #1e293b;
            color: #f1f5f9;
            border: 1px solid #334155;
            border-radius: 0.5rem;
        }
        .custom-map-popup .leaflet-popup-tip {
            background: #1e293b;
        }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <form action="{{ route('vehicle.storeRating', $plate_number) }}" method="POST" enctype="multipart/form-data" id="ratingForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Basic Info -->
                        <div>
                             <!-- Rating Stars -->
                            <div class="mb-8">
                                <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-3">THREAT_LEVEL_ASSESSMENT</label>
                                <div class="flex gap-2 p-4 bg-slate-950 border border-slate-800 rounded-lg justify-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer group text-center">
                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                            <i data-lucide="star" class="w-6 h-6 text-slate-700 fill-slate-900 peer-checked:text-amber-500 peer-checked:fill-amber-500 group-hover:text-amber-400 transition-colors mb-1"></i>
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
                            <div class="mb-8" x-data="tagManager()">
                                <label for="tags_input" class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">BEHAVIORAL_TAGS</label>
                                <div class="relative">
                                    <input type="text" id="tags_input" 
                                        @keydown.enter.prevent="addTag($event.target.value); $event.target.value = ''"
                                        @keydown.comma.prevent="addTag($event.target.value); $event.target.value = ''"
                                        class="block w-full px-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono text-sm transition-all"
                                        placeholder="Type & Enter to add (e.g. SPEEDING)">
                                    <input type="hidden" name="tags" :value="tags.join(',')">
                                    
                                    <!-- Suggestions -->
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <template x-for="suggestion in suggestions">
                                            <button type="button" @click="addTag(suggestion)" 
                                                class="px-2 py-1 bg-slate-800 text-[10px] font-mono text-slate-400 border border-slate-700 hover:border-cyan-500 hover:text-cyan-400 transition-colors">
                                                + <span x-text="suggestion"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Selected Tags -->
                                <div class="mt-3 flex flex-wrap gap-2 min-h-[2rem]">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span class="inline-flex items-center px-2 py-1 bg-cyan-900/30 border border-cyan-500/50 text-cyan-400 text-xs font-mono rounded">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="removeTag(index)" class="ml-2 hover:text-white">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Media & Location -->
                        <div>
                            <!-- Location -->
                            <div class="mb-8">
                                <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">LOCATION_DATA</label>
                                <div class="relative mb-2">
                                    <input type="text" id="addressSearch" placeholder="Search location..." 
                                        class="w-full px-3 py-2 bg-slate-950 border border-slate-700 text-slate-100 text-sm font-mono focus:border-cyan-500 focus:outline-none">
                                    <button type="button" id="searchBtn" class="absolute right-2 top-2 text-slate-400 hover:text-cyan-500">
                                        <i data-lucide="search" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <div id="map" class="h-48 w-full bg-slate-950 border border-slate-700 rounded-lg z-0"></div>
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <input type="hidden" name="address" id="address">
                                <div id="locationDisplay" class="mt-2 text-[10px] font-mono text-slate-500 truncate">No location selected</div>
                            </div>

                            <!-- Media Upload -->
                            <div class="mb-8" x-data="mediaUploader()">
                                <label class="block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide mb-2">EVIDENCE_UPLOAD</label>
                                
                                <div class="border-2 border-dashed border-slate-700 bg-slate-950/50 rounded-lg p-4 text-center hover:border-cyan-500/50 transition-colors cursor-pointer relative"
                                     @click="$refs.fileInput.click()">
                                    <input type="file" x-ref="fileInput" name="media[]" multiple accept="image/*,video/*" class="hidden" @change="handleFiles($event)">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-500 mx-auto mb-2"></i>
                                    <p class="text-xs text-slate-400 font-mono">Click to upload Image/Video</p>
                                    <p class="text-[10px] text-slate-600 font-mono mt-1">Max 10MB • 720p Min</p>
                                </div>

                                <!-- Previews -->
                                <div class="mt-4 space-y-3">
                                    <template x-for="(file, index) in files" :key="index">
                                        <div class="flex gap-3 items-start bg-slate-950 border border-slate-800 p-2 rounded">
                                            <div class="w-16 h-16 bg-slate-900 flex-shrink-0 border border-slate-800 flex items-center justify-center overflow-hidden">
                                                <template x-if="file.type.startsWith('image/')">
                                                    <img :src="file.preview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="file.type.startsWith('video/')">
                                                    <i data-lucide="video" class="w-6 h-6 text-slate-500"></i>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs text-slate-300 font-mono truncate block" x-text="file.name"></span>
                                                    <button type="button" @click="removeFile(index)" class="text-slate-600 hover:text-red-400">
                                                        <i data-lucide="x" class="w-3 h-3"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="media_captions[]" placeholder="Add caption..." 
                                                    class="w-full bg-slate-900 border-none text-[10px] text-slate-300 p-1 focus:ring-1 focus:ring-cyan-500 placeholder-slate-600 font-mono">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Honesty Declaration -->
                    <div class="mt-6 mb-8 p-4 bg-slate-950/80 border border-cyan-900/30 rounded">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" name="honesty_declaration" required 
                                class="mt-1 w-4 h-4 rounded border-slate-600 bg-slate-900 text-cyan-600 focus:ring-cyan-500 focus:ring-offset-slate-900">
                            <span class="text-xs text-slate-400 group-hover:text-slate-300 font-mono leading-relaxed">
                                <strong class="text-cyan-500">MANDATORY DECLARATION:</strong> I certify that this report is made truthfully and without malicious intent. I understand that submitting false surveillance data is a violation of the network protocols and may result in account termination.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-800">
                        <a href="{{ route('vehicle.show', $plate_number) }}" class="text-slate-500 hover:text-slate-300 font-mono text-xs uppercase tracking-wider">
                            < CANCEL_ENTRY
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-xs uppercase tracking-widest hover:bg-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition ease-in-out duration-150 shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                            SUBMIT_TO_ARCHIVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // --- Tag Manager ---
        function tagManager() {
            return {
                tags: [],
                suggestions: ['SPEEDING', 'AGGRESSIVE', 'RED_LIGHT', 'ILLEGAL_PARKING', 'DANGEROUS_OVERTAKE', 'PHONE_USE'],
                addTag(value) {
                    const tag = value.trim().toUpperCase();
                    if (tag && !this.tags.includes(tag)) {
                        this.tags.push(tag);
                    }
                },
                removeTag(index) {
                    this.tags.splice(index, 1);
                }
            }
        }

        // --- Media Uploader ---
        function mediaUploader() {
            return {
                files: [],
                handleFiles(event) {
                    const newFiles = Array.from(event.target.files);
                    
                    // Process new files
                    newFiles.forEach(file => {
                        if (file.size > 10 * 1024 * 1024) {
                            alert(`File ${file.name} exceeds 10MB limit.`);
                            return;
                        }

                        // Check duplicates
                        if (this.files.some(f => f.name === file.name && f.size === file.size)) {
                            return;
                        }

                        const fileObj = {
                            name: file.name,
                            type: file.type,
                            preview: '',
                            file: file
                        };
                        
                        this.files.push(fileObj);

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            fileObj.preview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });

                    this.updateInput();
                },
                removeFile(index) {
                    this.files.splice(index, 1);
                    this.updateInput();
                },
                updateInput() {
                    const dt = new DataTransfer();
                    this.files.forEach(f => dt.items.add(f.file));
                    this.$refs.fileInput.files = dt.files;
                }
            }
        }

        // --- Map Initialization ---
        document.addEventListener('DOMContentLoaded', function() {
            // Default to Jakarta (or project default)
            const defaultLat = -6.2088;
            const defaultLng = 106.8456;
            
            const map = L.map('map').setView([defaultLat, defaultLng], 13);
            
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            let marker;

            // Geolocation
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(position => {
                    const { latitude, longitude } = position.coords;
                    updateLocation(latitude, longitude);
                    map.setView([latitude, longitude], 15);
                });
            }

            function updateLocation(lat, lng, addr = '') {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                if (!addr) {
                    // Reverse geocode
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                        .then(res => res.json())
                        .then(data => {
                            const displayName = data.display_name;
                            document.getElementById('address').value = displayName;
                            document.getElementById('locationDisplay').textContent = displayName;
                        });
                } else {
                    document.getElementById('address').value = addr;
                    document.getElementById('locationDisplay').textContent = addr;
                }
            }

            map.on('click', function(e) {
                updateLocation(e.latlng.lat, e.latlng.lng);
            });

            // Search functionality
            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('addressSearch');

            function performSearch() {
                const query = searchInput.value;
                if (!query) return;

                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const { lat, lon, display_name } = data[0];
                            const latNum = parseFloat(lat);
                            const lngNum = parseFloat(lon);
                            map.setView([latNum, lngNum], 15);
                            updateLocation(latNum, lngNum, display_name);
                        } else {
                            alert('Location not found');
                        }
                    });
            }

            searchBtn.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        });
    </script>
</x-app-layout>
