<x-app-layout>
    @section('title', 'Vehicle Profile | MATAJALAN_OS')
    <x-slot name="header">
        {{ __('TARGET_PROFILE') }} // {{ $vehicle ? $vehicle->plate_number : $plate_number }}
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        .leaflet-container {
            background: #0f172a;
        }
        .leaflet-popup-content-wrapper {
            background: #0f172a; /* Slate-900 */
            color: #f1f5f9;
            border: 1px solid #1e293b; /* Slate-800 */
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            padding: 0;
            overflow: hidden;
        }
        .leaflet-popup-content {
            margin: 0;
            line-height: 1.5;
        }
        .leaflet-popup-tip {
            background: #1e293b; /* Slate-800 to match border */
        }
        .rating-popup-enter {
            animation: popup-scale 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes popup-scale {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>

    <div class="py-12" x-data="{
        isOpen: false,
        activeMedia: null,
        mediaList: [],
        currentIndex: 0,
        
        openViewer(items, index) {
            this.mediaList = items;
            this.currentIndex = index;
            this.activeMedia = this.mediaList[this.currentIndex];
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        closeViewer() {
            this.isOpen = false;
            this.activeMedia = null;
            document.body.style.overflow = '';
        },
        
        next() {
            if (this.mediaList.length <= 1) return;
            if (this.currentIndex < this.mediaList.length - 1) {
                this.currentIndex++;
            } else {
                this.currentIndex = 0;
            }
            this.activeMedia = this.mediaList[this.currentIndex];
        },
        
        prev() {
            if (this.mediaList.length <= 1) return;
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else {
                this.currentIndex = this.mediaList.length - 1;
            }
            this.activeMedia = this.mediaList[this.currentIndex];
        }
    }" @keydown.escape.window="closeViewer()" @keydown.right.window="next()" @keydown.left.window="prev()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Profile Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg shadow-2xl overflow-hidden mb-6 relative">
                <!-- Status Bar -->
                <div class="h-1 w-full bg-gradient-to-r from-cyan-500 via-blue-500 to-purple-500"></div>
                
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h1 class="text-4xl font-mono font-bold text-slate-100 tracking-tight">{{ $vehicle ? $vehicle->plate_number : $plate_number }}</h1>
                                @if($vehicle && $vehicle->ratings_avg_rating && $vehicle->ratings_avg_rating < 2.5)
                                    <span class="px-2 py-1 bg-red-900/30 border border-red-500/50 text-red-400 text-[10px] font-mono font-bold rounded">HIGH_THREAT</span>
                                @else
                                    <span class="px-2 py-1 bg-emerald-900/30 border border-emerald-500/50 text-emerald-400 text-[10px] font-mono font-bold rounded">SAFE_LEVEL</span>
                                @endif
                            </div>
                            <p class="text-xs font-mono text-slate-500 uppercase">Vehicle Identification Number // Verified in Database</p>
                        </div>
                        
                        <div class="flex gap-2">
                            @auth
                                @if($vehicle)
                                    <a href="{{ route('vehicle.edit', $vehicle->uuid) }}" class="inline-flex items-center justify-center px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-mono font-bold text-xs uppercase tracking-wider transition-all border border-slate-700 hover:border-cyan-500/50">
                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                        EDIT_INFO
                                    </a>
                                    <a href="{{ route('vehicle.user.create', $vehicle->uuid) }}" class="inline-flex items-center justify-center px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-mono font-bold text-xs uppercase tracking-wider transition-all border border-slate-700 hover:border-cyan-500/50">
                                        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                        REGISTER_AS_USER
                                    </a>
                                @endif
                            @endauth
                            <a href="{{ route('vehicle.rate', $vehicle ? $vehicle->uuid : $plate_number) }}" class="inline-flex items-center justify-center px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-wider transition-all clip-path-polygon hover:shadow-[0_0_15px_rgba(6,182,212,0.5)]">
                                <i data-lucide="file-plus" class="w-4 h-4 mr-2"></i>
                                SUBMIT_REPORT
                            </a>
                        </div>
                    </div>

                    @if($vehicle)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-slate-950 border border-slate-800 p-4 rounded text-center">
                                <div class="text-xs text-slate-500 font-mono uppercase mb-1">Safety Score</div>
                                <div class="text-3xl font-bold text-white font-mono">{{ round($vehicle->ratings_avg_rating * 20) }}<span class="text-sm text-slate-600">/100</span></div>
                            </div>
                            <div class="bg-slate-900 border border-slate-800 p-4 rounded text-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-amber-500/5 opacity-0 hover:opacity-100 transition-opacity"></div>
                                <div class="text-xs text-slate-500 font-mono uppercase mb-1">Average Rating</div>
                                <div class="text-3xl font-bold text-amber-400 font-mono flex items-center justify-center gap-2">
                                    {{ number_format($vehicle->ratings_avg_rating, 1) }}
                                    <i data-lucide="star" class="w-5 h-5 fill-amber-400 text-amber-400"></i>
                                </div>
                            </div>
                            <div class="bg-slate-950 border border-slate-800 p-4 rounded text-center">
                                <div class="text-xs text-slate-500 font-mono uppercase mb-1">Total Reports</div>
                                <div class="text-3xl font-bold text-cyan-400 font-mono">{{ $vehicle->ratings_count }}</div>
                            </div>
                        </div>

                        <!-- Map Visualization -->
                        <div class="mb-8 border border-slate-800 rounded overflow-hidden">
                            <div class="bg-slate-950 px-4 py-2 border-b border-slate-800 flex items-center gap-2">
                                <i data-lucide="map" class="w-4 h-4 text-cyan-500"></i>
                                <span class="text-xs font-mono font-bold text-slate-400 uppercase">Incident Heatmap</span>
                            </div>
                            <div id="vehicleMap" class="h-64 w-full bg-slate-900"></div>
                        </div>

                        <!-- Vehicle Specifications Section -->
                        <div class="mb-8 bg-slate-900 border border-slate-800 rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                                <h2 class="text-lg font-mono font-bold text-slate-100 flex items-center gap-2">
                                    <i data-lucide="cpu" class="text-cyan-500"></i>
                                    VEHICLE_SPECIFICATIONS
                                </h2>
                                @if($canViewSpecs)
                                    <span class="px-2 py-1 bg-emerald-900/30 text-emerald-400 text-[10px] font-mono rounded border border-emerald-500/30">ACCESS_GRANTED</span>
                                @else
                                    <span class="px-2 py-1 bg-red-900/30 text-red-400 text-[10px] font-mono rounded border border-red-500/30">RESTRICTED_DATA</span>
                                @endif
                            </div>

                            @if($canViewSpecs)
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <!-- Core Specs -->
                                        <div>
                                            <h3 class="text-xs font-mono text-slate-500 uppercase mb-4 border-b border-slate-800 pb-2">Core Identifiers</h3>
                                            <dl class="grid grid-cols-2 gap-4 text-sm font-mono">
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Manufacturer</dt>
                                                    <dd class="text-slate-200">{{ $vehicle->make }}</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Model</dt>
                                                    <dd class="text-slate-200">{{ $vehicle->model }}</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Year</dt>
                                                    <dd class="text-slate-200">{{ $vehicle->year }}</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Color</dt>
                                                    <dd class="text-slate-200 flex items-center gap-2">
                                                        <span class="w-3 h-3 rounded-full border border-slate-600" style="background-color: {{ strtolower($vehicle->color) }}"></span>
                                                        {{ $vehicle->color }}
                                                    </dd>
                                                </div>
                                                <div class="col-span-2">
                                                    <dt class="text-slate-500 text-[10px] uppercase">VIN</dt>
                                                    <dd class="text-slate-200 tracking-wider">{{ $vehicle->vin }}</dd>
                                                </div>
                                            </dl>
                                        </div>

                                        <!-- Technical Specs (Mocked for Demo) -->
                                        <div>
                                            <h3 class="text-xs font-mono text-slate-500 uppercase mb-4 border-b border-slate-800 pb-2">Technical Data (System Estimate)</h3>
                                            <dl class="grid grid-cols-2 gap-4 text-sm font-mono">
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Engine</dt>
                                                    <dd class="text-slate-200">2.0L Inline-4 Turbo</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Horsepower</dt>
                                                    <dd class="text-slate-200">184 hp @ 5000 rpm</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Drivetrain</dt>
                                                    <dd class="text-slate-200">FWD / CVT</dd>
                                                </div>
                                                <div>
                                                    <dt class="text-slate-500 text-[10px] uppercase">Fuel Economy</dt>
                                                    <dd class="text-slate-200">28 MPG (Combined)</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8">
                                        <h3 class="text-xs font-mono text-slate-500 uppercase mb-4 border-b border-slate-800 pb-2">Safety & Features</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="bg-slate-950 p-3 rounded border border-slate-800">
                                                <div class="text-slate-500 text-[10px] uppercase mb-1">Safety Rating</div>
                                                <div class="flex text-amber-400">
                                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                    <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                </div>
                                            </div>
                                            <div class="bg-slate-950 p-3 rounded border border-slate-800">
                                                <div class="text-slate-500 text-[10px] uppercase mb-1">Airbags</div>
                                                <div class="text-slate-200 font-mono">8 Standard</div>
                                            </div>
                                            <div class="bg-slate-950 p-3 rounded border border-slate-800">
                                                <div class="text-slate-500 text-[10px] uppercase mb-1">Assistance</div>
                                                <div class="text-slate-200 font-mono">ABS, EBD, TCS</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-8 text-center bg-slate-950/50">
                                    <div class="w-16 h-16 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-800">
                                        <i data-lucide="lock" class="w-8 h-8 text-slate-600"></i>
                                    </div>
                                    <h3 class="text-lg font-mono font-bold text-slate-300 mb-2">ACCESS_DENIED</h3>
                                    <p class="text-sm text-slate-500 font-mono mb-6 max-w-md mx-auto">
                                        Detailed vehicle specifications are classified. Access is restricted to Tier-1 Verified Users and System Administrators.
                                    </p>
                                    @auth
                                        @if(!Auth::user()->hasRole('tier_1') && !Auth::user()->isAdmin())
                                            <a href="{{ route('kyc.index') }}" class="inline-flex items-center justify-center px-6 py-2 bg-slate-800 border border-slate-700 text-cyan-400 font-mono font-bold text-xs uppercase tracking-widest hover:bg-slate-700 hover:border-cyan-500 transition-all">
                                                UPGRADE_CLEARANCE (KYC)
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2 bg-slate-800 border border-slate-700 text-cyan-400 font-mono font-bold text-xs uppercase tracking-widest hover:bg-slate-700 hover:border-cyan-500 transition-all">
                                            AUTHENTICATE_USER
                                        </a>
                                    @endauth
                                </div>
                            @endif
                        </div>

                        <!-- Authorized Drivers Section -->
                        <div class="mb-8 bg-slate-900 border border-slate-800 rounded-lg overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                                <h2 class="text-lg font-mono font-bold text-slate-100 flex items-center gap-2">
                                    <i data-lucide="users" class="text-cyan-500"></i>
                                    AUTHORIZED_DRIVERS
                                </h2>
                                @if($canViewSpecs)
                                    <span class="px-2 py-1 bg-emerald-900/30 text-emerald-400 text-[10px] font-mono rounded border border-emerald-500/30">ACCESS_GRANTED</span>
                                @else
                                    <span class="px-2 py-1 bg-red-900/30 text-red-400 text-[10px] font-mono rounded border border-red-500/30">RESTRICTED_DATA</span>
                                @endif
                            </div>

                            @if($canViewSpecs)
                                <div class="p-6">
                                    @php
                                        $approvedDrivers = $vehicle->vehicleUsers->where('status', 'approved');
                                    @endphp
                                    @if($approvedDrivers->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left border-collapse">
                                                <thead>
                                                    <tr class="border-b border-slate-800 text-xs font-mono text-slate-500 uppercase">
                                                        <th class="py-2 px-4">Driver Name</th>
                                                        <th class="py-2 px-4">Role Type</th>
                                                        <th class="py-2 px-4">Registered By</th>
                                                        <th class="py-2 px-4">Verified At</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-sm font-mono text-slate-300">
                                                    @foreach($approvedDrivers as $vUser)
                                                        <tr class="border-b border-slate-800/50 hover:bg-slate-800/30 transition-colors">
                                                            <td class="py-3 px-4 font-bold text-white">{{ $vUser->driver_name }}</td>
                                                            <td class="py-3 px-4">
                                                                <span class="px-2 py-1 bg-slate-800 text-slate-400 text-[10px] rounded border border-slate-700 uppercase">
                                                                    {{ str_replace('_', ' ', $vUser->role_type) }}
                                                                </span>
                                                            </td>
                                                            <td class="py-3 px-4 flex items-center gap-2">
                                                                <div class="w-6 h-6 rounded bg-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                                    {{ substr($vUser->user->name ?? '?', 0, 1) }}
                                                                </div>
                                                                {{ $vUser->user->name ?? 'Unknown' }}
                                                            </td>
                                                            <td class="py-3 px-4 text-slate-500 text-xs">
                                                                {{ $vUser->reviewed_at ? $vUser->reviewed_at->format('M d, Y') : 'N/A' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <i data-lucide="user-x" class="w-8 h-8 text-slate-600 mx-auto mb-2"></i>
                                            <p class="text-slate-500 font-mono text-sm">No authorized drivers found for this vehicle.</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-8 text-center bg-slate-950/50">
                                    <div class="w-16 h-16 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-800">
                                        <i data-lucide="shield-alert" class="w-8 h-8 text-slate-600"></i>
                                    </div>
                                    <h3 class="text-lg font-mono font-bold text-slate-300 mb-2">ACCESS_DENIED</h3>
                                    <p class="text-sm text-slate-500 font-mono mb-6 max-w-md mx-auto">
                                        Driver identity information is classified. Access is restricted to Tier-1 Verified Users and System Administrators.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-slate-800 pt-8">
                            <h2 class="text-lg font-mono font-bold text-slate-100 mb-6 flex items-center gap-2">
                                <i data-lucide="list" class="text-slate-500"></i>
                                INCIDENT_LOGS
                            </h2>
                            
                            <div class="space-y-6">
                                @foreach($ratings as $rating)
                                    <div class="bg-slate-950 border border-slate-800 p-6 rounded hover:border-slate-700 transition-colors group relative overflow-hidden">
                                        
                                        @if($rating->is_honest)
                                            <div class="absolute top-0 right-0">
                                                <div class="bg-cyan-900/20 text-cyan-500 text-[10px] font-mono uppercase px-2 py-1 rounded-bl border-l border-b border-cyan-500/30 flex items-center gap-1">
                                                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                                                    Verified Truthful
                                                </div>
                                            </div>
                                        @endif

                                        @if($rating->status === 'pending')
                                            <div class="absolute top-0 right-0 {{ $rating->is_honest ? 'mr-32' : '' }}">
                                                <div class="bg-yellow-900/20 text-yellow-500 text-[10px] font-mono uppercase px-2 py-1 rounded-bl border-l border-b border-yellow-500/30 flex items-center gap-1">
                                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                                    Pending Approval
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded bg-slate-900 flex items-center justify-center font-mono font-bold text-slate-500 border border-slate-800">
                                                    {{ substr($rating->user->name ?? 'A', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-mono font-bold text-slate-300 text-sm">{{ $rating->user->name ?? 'Anonymous Agent' }}</div>
                                                    <div class="text-[10px] font-mono text-slate-600 uppercase flex items-center gap-2">
                                                        <span>{{ $rating->created_at->diffForHumans() }}</span>
                                                        @if($rating->address)
                                                            <span class="text-slate-700">|</span>
                                                            <span class="flex items-center gap-1 text-slate-500">
                                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                                {{ Str::limit($rating->address, 30) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex gap-0.5 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star" class="w-3 h-3 {{ $i <= $rating->rating ? 'fill-amber-500 text-amber-500' : 'text-slate-800' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        <div class="pl-13 ml-13 md:pl-13">
                                            <p class="text-slate-400 text-sm leading-relaxed mb-4 font-mono pl-4 border-l-2 border-slate-800">
                                                "{{ $rating->comment }}"
                                            </p>

                                            <!-- Media Gallery -->
                                            @if($rating->media->count() > 0)
                                                @php
                                                    $galleryJson = $rating->media->map(function($m) {
                                                        return [
                                                            'type' => $m->file_type,
                                                            'url' => Storage::url($m->file_path),
                                                            'caption' => $m->caption
                                                        ];
                                                    })->toJson();
                                                @endphp
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4" x-data="{ gallery: {{ $galleryJson }} }">
                                                    @foreach($rating->media as $index => $media)
                                                        <div @click="openViewer(gallery, {{ $index }})" class="relative group/media aspect-video bg-slate-900 rounded border border-slate-800 overflow-hidden cursor-pointer transition-all hover:border-cyan-500/50">
                                                            @if($media->file_type === 'image')
                                                                <img src="{{ Storage::url($media->file_path) }}" class="w-full h-full object-cover transition-transform group-hover/media:scale-105">
                                                            @else
                                                                <video src="{{ Storage::url($media->file_path) }}" class="w-full h-full object-cover"></video>
                                                                <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                                                                    <i data-lucide="play-circle" class="w-8 h-8 text-white/80"></i>
                                                                </div>
                                                            @endif
                                                            
                                                            @if($media->caption)
                                                                <div class="absolute bottom-0 left-0 right-0 bg-black/70 p-1 text-[10px] text-slate-300 font-mono truncate opacity-0 group-hover/media:opacity-100 transition-opacity">
                                                                    {{ $media->caption }}
                                                                </div>
                                                            @endif
                                                            
                                                            <!-- Hover overlay to indicate clickable -->
                                                            <div class="absolute inset-0 bg-cyan-500/10 opacity-0 group-hover/media:opacity-100 transition-opacity flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white/80 drop-shadow-lg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M14 10l6.1-6.1"/><path d="M9 21H3v-6"/><path d="M10 14l-6.1 6.1"/></svg>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            @if($rating->tags)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($rating->tags as $tag)
                                                        <span class="px-2 py-0.5 bg-cyan-900/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-mono uppercase rounded hover:bg-cyan-900/30 transition-colors cursor-default">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $ratings->links('vendor.pagination.cyberpunk') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16 border-2 border-dashed border-slate-800 rounded-lg bg-slate-900/50">
                            <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                                <div class="absolute inset-0 bg-red-500/10 rounded-full animate-pulse"></div>
                                <i data-lucide="search-x" class="w-10 h-10 text-slate-500"></i>
                            </div>
                            
                            <h3 class="text-xl font-mono font-bold text-slate-200 mb-2">TARGET_NOT_FOUND</h3>
                            <p class="text-sm text-slate-500 font-mono mb-8 max-w-md mx-auto">
                                The requested vehicle identifier <span class="text-cyan-400 font-bold">{{ $plate_number }}</span> does not exist in our central database.
                            </p>

                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                <a href="{{ route('vehicle.create', $plate_number) }}" class="inline-flex items-center justify-center px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-wider transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">
                                    <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                                    REGISTER_NEW_TARGET
                                </a>
                                
                                <span class="text-slate-600 font-mono text-xs uppercase">OR</span>
                                
                                <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-300 font-mono text-sm uppercase underline decoration-slate-700 hover:decoration-slate-500 transition-all">
                                    RETURN_TO_SEARCH
                                </a>
                            </div>

                            <div class="mt-8 p-4 bg-slate-950/50 border border-slate-800/50 rounded inline-block text-left">
                                <p class="text-[10px] text-slate-500 font-mono uppercase mb-2">REQUIRED_DATA_POINTS:</p>
                                <ul class="text-[10px] text-slate-400 font-mono space-y-1">
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-3 h-3 text-emerald-500"></i> Manufacturer & Model</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-3 h-3 text-emerald-500"></i> Production Year</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-3 h-3 text-emerald-500"></i> Visual Identifier (Color)</li>
                                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-3 h-3 text-emerald-500"></i> VIN (Vehicle ID Number)</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Full Screen Media Viewer Modal -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex items-center justify-center p-4"
             style="display: none;">
            
            <!-- Close Button -->
            <button @click="closeViewer()" class="absolute top-4 right-4 text-slate-400 hover:text-white bg-slate-900/50 hover:bg-slate-800 p-2 rounded-full transition-colors z-[101]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>

            <!-- Navigation Buttons -->
            <button x-show="mediaList.length > 1" @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white bg-slate-900/50 hover:bg-slate-800 p-3 rounded-full transition-colors z-[101]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>

            <button x-show="mediaList.length > 1" @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white bg-slate-900/50 hover:bg-slate-800 p-3 rounded-full transition-colors z-[101]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </button>

            <!-- Main Content -->
            <div class="relative max-w-7xl max-h-full w-full flex flex-col items-center justify-center" @click.outside="closeViewer()">
                <template x-if="activeMedia && activeMedia.type === 'image'">
                    <img :src="activeMedia.url" class="max-h-[85vh] max-w-full object-contain shadow-2xl rounded-sm">
                </template>
                
                <template x-if="activeMedia && activeMedia.type === 'video'">
                    <video :src="activeMedia.url" controls autoplay class="max-h-[85vh] max-w-full shadow-2xl rounded-sm"></video>
                </template>

                <!-- Caption / Info -->
                <div x-show="activeMedia && activeMedia.caption" class="mt-4 bg-slate-900/80 border border-slate-800 text-slate-200 px-4 py-2 rounded-lg font-mono text-sm max-w-2xl text-center backdrop-blur-sm">
                    <span x-text="activeMedia.caption"></span>
                </div>

                <!-- Counter -->
                <div x-show="mediaList.length > 1" class="absolute -bottom-12 left-1/2 -translate-x-1/2 text-slate-500 font-mono text-xs">
                    <span x-text="currentIndex + 1"></span> / <span x-text="mediaList.length"></span>
                </div>
            </div>
        </div>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Secure: Do not expose full vehicle object to JS as it contains restricted specs
            const ratings = @json($vehicle ? $vehicle->ratings : []);
            
            // Only init map if there are ratings with location
            const locations = ratings.filter(r => r.latitude && r.longitude);
            
            if (locations.length > 0) {
                const map = L.map('vehicleMap');
                
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; OpenStreetMap &copy; CARTO',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(map);

                const bounds = L.latLngBounds();
                
                locations.forEach(loc => {
                    const lat = parseFloat(loc.latitude);
                    const lng = parseFloat(loc.longitude);
                    bounds.extend([lat, lng]);
                    
                    // Generate stars HTML
                    let starsHtml = '';
                    for(let i=1; i<=5; i++) {
                        const fillClass = i <= loc.rating ? 'fill-amber-400 text-amber-400' : 'text-slate-700';
                        starsHtml += `<svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ${fillClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;
                    }
                    
                    const category = loc.tags && loc.tags.length > 0 ? loc.tags[0].toUpperCase() : 'VERIFIED RATING';
                    const dateStr = new Date(loc.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    
                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup(`
                            <div class="rating-popup-enter p-4 min-w-[160px] text-center" role="dialog" aria-label="Rating Details">
                                <div class="text-[10px] font-mono text-cyan-500 uppercase mb-2 tracking-wider border-b border-slate-800 pb-1">${category}</div>
                                <div class="flex items-baseline justify-center gap-1 mb-2">
                                    <span class="text-4xl font-bold font-mono text-white leading-none tracking-tighter" aria-label="Rating ${loc.rating} out of 5">${loc.rating}</span>
                                    <span class="text-xs font-mono text-slate-500">/5.0</span>
                                </div>
                                <div class="flex justify-center gap-1 mb-3 bg-slate-950 p-1.5 rounded border border-slate-800 inline-flex" aria-hidden="true">
                                    ${starsHtml}
                                </div>
                                <div class="text-[10px] text-slate-500 font-mono">
                                    ${dateStr}
                                </div>
                            </div>
                        `, { 
                            className: 'custom-map-popup',
                            minWidth: 160
                        });
                });
                
                map.fitBounds(bounds, { padding: [50, 50] });
            } else {
                // Default view if no location data
                if(document.getElementById('vehicleMap')) {
                    const map = L.map('vehicleMap').setView([-6.2088, 106.8456], 11);
                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; OpenStreetMap &copy; CARTO',
                        subdomains: 'abcd'
                    }).addTo(map);
                }
            }
        });
    </script>
</x-app-layout>
