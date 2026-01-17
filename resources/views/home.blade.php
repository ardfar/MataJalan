<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MATAJALAN_OS // Surveillance System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Icons & Charts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Cyberpunk Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #06b6d4; }

        /* Tech Utilities */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #0891b2 1px, transparent 1px), linear-gradient(to bottom, #0891b2 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .tech-border-b {
            position: relative;
            border-bottom: 1px solid #1e293b;
        }
        .tech-border-b::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 20px;
            height: 1px;
            background: #06b6d4;
            box-shadow: 0 0 10px #06b6d4;
        }
        
        /* Nav Animation */
        #mainNav { transition: all 0.3s ease-in-out; }
        .nav-scrolled { background-color: rgba(2, 6, 23, 0.9); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(30, 41, 59, 0.5); }
    </style>
</head>
<body class="bg-slate-950 text-slate-300 font-sans selection:bg-cyan-500/30 selection:text-cyan-200 overflow-x-hidden">

    <!-- Background Grid -->
    <div class="fixed inset-0 pointer-events-none z-0 opacity-10 bg-grid-pattern"></div>

    <!-- NAVIGATION -->
    <nav id="mainNav" class="fixed top-0 w-full z-50 border-b border-transparent h-16">
        <div class="container mx-auto px-6 h-full flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 bg-cyan-900/20 border border-cyan-500 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-cyan-500/20 translate-y-full group-hover:translate-y-0 transition-transform"></div>
                    <i data-lucide="eye" class="w-5 h-5 text-cyan-400 relative z-10"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-100 tracking-wider font-mono">MATA<span class="text-cyan-500">JALAN</span>_OS</h1>
                </div>
            </a>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-8 font-mono text-xs tracking-wider">
                <a href="#high-threat" class="text-slate-400 hover:text-cyan-400 transition-colors uppercase">High Threats</a>
                <a href="#analytics" class="text-slate-400 hover:text-cyan-400 transition-colors uppercase">Analytics</a>
                <a href="#feed" class="text-slate-400 hover:text-cyan-400 transition-colors uppercase flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Live Feed
                </a>
            </div>

            <!-- Auth / Status -->
            <div class="flex items-center gap-4">
                <div class="hidden lg:flex flex-col items-end text-[10px] font-mono leading-tight">
                    <span class="text-slate-500">SYS_STATUS</span>
                    <span class="text-emerald-500">ONLINE</span>
                </div>
                
                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-1.5 border border-slate-700 hover:border-cyan-500 bg-slate-900 transition-all rounded-sm">
                            <span class="text-xs font-bold text-cyan-500 font-mono">{{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-3 h-3 text-slate-500"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-800 shadow-xl hidden group-hover:block rounded-sm overflow-hidden">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-xs font-mono hover:bg-slate-800 text-slate-300">DASHBOARD</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-mono hover:bg-slate-800 text-red-400">
                                    LOGOUT
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 font-mono text-xs">
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-cyan-400">LOGIN</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-cyan-900/20 border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500 hover:text-white transition-all">ACCESS</a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-slate-400 hover:text-white" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-slate-900 border-b border-slate-800 p-4 absolute w-full">
            <div class="flex flex-col gap-4 font-mono text-sm">
                <a href="#high-threat" class="text-slate-400 hover:text-cyan-400">HIGH THREATS</a>
                <a href="#analytics" class="text-slate-400 hover:text-cyan-400">ANALYTICS</a>
                <a href="#feed" class="text-slate-400 hover:text-cyan-400">LIVE FEED</a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="pt-32 pb-20 px-6 relative overflow-hidden">
        <div class="container mx-auto max-w-5xl text-center relative z-10">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-950/30 border border-cyan-500/30 text-cyan-400 text-xs font-mono mb-8 animate-pulse">
                <div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div>
                SYSTEM V2.0 // READY
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-slate-100 mb-6 tracking-tight leading-tight">
                INTELLIGENT <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-600">SURVEILLANCE</span> NETWORK
            </h1>
            
            <p class="text-slate-400 text-lg max-w-2xl mx-auto mb-12 font-light">
                Access the national vehicle database. Report incidents, track violations, and monitor safety scores in real-time.
            </p>

            <!-- Search Component -->
            <div class="max-w-2xl mx-auto relative group z-20">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                <div class="relative bg-slate-950 rounded-lg p-1 flex items-center">
                    <div class="pl-4 pr-3">
                        <i data-lucide="search" class="w-5 h-5 text-slate-500"></i>
                    </div>
                    <input 
                        type="text" 
                        id="heroSearch"
                        class="block w-full bg-transparent border-none text-slate-100 text-lg placeholder-slate-600 focus:outline-none focus:ring-0 font-mono uppercase py-3"
                        placeholder="SEARCH LICENSE PLATE..."
                        autocomplete="off"
                    >
                    <button class="px-6 py-2 bg-slate-800 hover:bg-cyan-600 text-white font-mono text-sm rounded transition-colors mr-1">
                        SCAN
                    </button>
                </div>
                
                <!-- Autocomplete Dropdown -->
                <div id="searchResults" class="absolute top-full left-0 right-0 mt-2 bg-slate-900 border border-slate-700 rounded-lg shadow-2xl hidden max-h-60 overflow-y-auto z-50">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
    </section>

    <!-- HIGH THREAT SECTION -->
    <section id="high-threat" class="py-16 bg-slate-900/30 border-y border-slate-800/50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-mono font-bold text-white flex items-center gap-3">
                    <i data-lucide="siren" class="text-red-500"></i>
                    HIGH_PRIORITY_TARGETS
                </h2>
                <div class="flex gap-2">
                    <span class="px-2 py-1 text-[10px] font-mono border border-red-500/30 text-red-400 bg-red-950/20 rounded">LEVEL: CRITICAL</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                @forelse($highThreatVehicles as $vehicle)
                    <div class="bg-slate-950 border border-red-900/30 hover:border-red-500/50 p-4 rounded group transition-all relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-2 opacity-50 group-hover:opacity-100">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        </div>
                        <h3 class="text-xl font-mono font-bold text-slate-100 mb-1">{{ $vehicle->plate }}</h3>
                        <p class="text-xs text-slate-500 uppercase font-mono mb-3">{{ $vehicle->model }}</p>
                        
                        <div class="flex items-center justify-between text-xs border-t border-slate-800 pt-3">
                            <span class="text-red-400 font-bold">{{ $vehicle->reports }} REPORTS</span>
                            <span class="text-slate-600">{{ $vehicle->score }} SCORE</span>
                        </div>
                        
                        <a href="{{ route('vehicle.show', $vehicle->plate) }}" class="absolute inset-0 z-10"></a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-slate-600 font-mono border border-slate-800 border-dashed rounded">
                        NO HIGH THREATS DETECTED
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ANALYTICS SECTION -->
    <section id="analytics" class="py-20 px-6">
        <div class="container mx-auto">
            <div class="tech-border-b mb-10 pb-4">
                <h2 class="text-2xl font-mono font-bold text-white">SYSTEM_ANALYTICS</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Chart 1: Threat Distribution -->
                <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-lg">
                    <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">Threat Distribution</h3>
                    <div class="h-64">
                        <canvas id="threatChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Activity -->
                <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-lg">
                    <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">24H Activity</h3>
                    <div class="h-64">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Top Tags -->
                <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-lg">
                    <h3 class="text-sm font-mono text-slate-400 mb-4 uppercase">Common Violations</h3>
                    <div class="h-64">
                        <canvas id="tagsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="py-20 px-6 border-t border-slate-800">
        <div class="container mx-auto max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="p-6">
                    <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-800">
                        <i data-lucide="camera" class="text-cyan-500"></i>
                    </div>
                    <h3 class="text-white font-bold mb-2">Automated Recognition</h3>
                    <p class="text-sm text-slate-500">Real-time license plate detection powered by neural networks.</p>
                </div>
                <div class="p-6">
                    <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-800">
                        <i data-lucide="shield" class="text-cyan-500"></i>
                    </div>
                    <h3 class="text-white font-bold mb-2">Threat Assessment</h3>
                    <p class="text-sm text-slate-500">Dynamic risk scoring based on historical behavioral data.</p>
                </div>
                <div class="p-6">
                    <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-800">
                        <i data-lucide="users" class="text-cyan-500"></i>
                    </div>
                    <h3 class="text-white font-bold mb-2">Crowd Intelligence</h3>
                    <p class="text-sm text-slate-500">Community-driven reporting system for verified incidents.</p>
                </div>
                <div class="p-6">
                    <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-800">
                        <i data-lucide="database" class="text-cyan-500"></i>
                    </div>
                    <h3 class="text-white font-bold mb-2">Secure Archive</h3>
                    <p class="text-sm text-slate-500">Encrypted storage for all surveillance logs and evidence.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 border-t border-slate-900 py-12 px-6">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h4 class="text-slate-100 font-mono font-bold mb-1">MATAJALAN_OS</h4>
                <p class="text-xs text-slate-600 font-mono">GOVERNMENT SURVEILLANCE INITIATIVE</p>
            </div>
            
            <div class="flex gap-8 text-xs font-mono text-slate-500">
                <a href="#" class="hover:text-cyan-400">PRIVACY_PROTOCOL</a>
                <a href="#" class="hover:text-cyan-400">TERMS_OF_ENGAGEMENT</a>
                <a href="#" class="hover:text-cyan-400">CONTACT_ADMIN</a>
            </div>

            <div class="text-xs text-slate-700 font-mono">
                &copy; 2026 MATAJALAN SYSTEM. ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        // --- 1. Navigation Scroll Effect ---
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                nav.classList.add('nav-scrolled');
            } else {
                nav.classList.remove('nav-scrolled');
            }
        });

        // --- 2. Autocomplete Search ---
        const vehiclesData = @json($vehicles);
        const searchInput = document.getElementById('heroSearch');
        const resultsBox = document.getElementById('searchResults');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            const matches = vehiclesData.filter(v => 
                v.plate.toLowerCase().includes(query) || 
                v.model.toLowerCase().includes(query)
            ).slice(0, 5);

            if (matches.length > 0) {
                resultsBox.innerHTML = matches.map(v => `
                    <a href="/vehicle/${v.plate}" class="block p-3 hover:bg-slate-800 border-b border-slate-800 last:border-0 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-mono font-bold text-slate-200">${v.plate}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded ${v.threatLevel === 'HIGH' ? 'bg-red-900/30 text-red-400' : 'bg-emerald-900/30 text-emerald-400'}">${v.threatLevel}</span>
                        </div>
                        <div class="text-xs text-slate-500 uppercase font-mono">${v.model}</div>
                    </a>
                `).join('');
                resultsBox.classList.remove('hidden');
            } else {
                resultsBox.innerHTML = '<div class="p-3 text-xs text-slate-500 font-mono text-center">NO DATA FOUND</div>';
                resultsBox.classList.remove('hidden');
            }
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.classList.add('hidden');
            }
        });

        // --- 3. Charts (Chart.js) ---
        const analytics = @json($analytics);
        
        Chart.defaults.font.family = "'JetBrains Mono', monospace";
        Chart.defaults.color = '#64748b';
        Chart.defaults.borderColor = '#1e293b';

        // A. Threat Distribution (Pie)
        new Chart(document.getElementById('threatChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [analytics.threats.HIGH, analytics.threats.MEDIUM, analytics.threats.LOW],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });

        // B. Activity (Line)
        new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: Object.keys(analytics.activity),
                datasets: [{
                    label: 'Reports',
                    data: Object.values(analytics.activity),
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, grid: { color: '#1e293b' } }, x: { grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });

        // C. Top Tags (Bar)
        new Chart(document.getElementById('tagsChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(analytics.tags),
                datasets: [{
                    label: 'Count',
                    data: Object.values(analytics.tags),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: { x: { display: false }, y: { grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });

        // Initialize Icons
        lucide.createIcons();
    </script>
</body>
</html>