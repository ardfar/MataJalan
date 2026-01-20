<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MATAJALAN_OS // COMMAND_CENTER</title>

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
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #06b6d4; }
        
        .admin-grid {
            background-image: linear-gradient(to right, #1e293b 1px, transparent 1px), linear-gradient(to bottom, #1e293b 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-300 font-sans selection:bg-cyan-500/30 selection:text-cyan-200 h-screen flex overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950">
            <i data-lucide="shield" class="w-6 h-6 text-cyan-500 mr-3"></i>
            <span class="font-mono font-bold text-slate-100 tracking-wider">COMMAND_CTR</span>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-mono rounded hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-cyan-400 border-l-2 border-cyan-500' : 'text-slate-400 border-l-2 border-transparent' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                OVERVIEW
            </a>
            
            <div class="pt-4 pb-2 px-4 text-[10px] font-mono text-slate-600 uppercase">Management</div>
            
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-mono rounded hover:bg-slate-800 {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-cyan-400 border-l-2 border-cyan-500' : 'text-slate-400 border-l-2 border-transparent' }}">
                <i data-lucide="users" class="w-4 h-4"></i>
                OPERATIVES (USERS)
            </a>
            
            <a href="{{ route('admin.kyc.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-mono rounded hover:bg-slate-800 {{ request()->routeIs('admin.kyc.*') ? 'bg-slate-800 text-cyan-400 border-l-2 border-cyan-500' : 'text-slate-400 border-l-2 border-transparent' }}">
                <i data-lucide="file-check" class="w-4 h-4"></i>
                KYC_VERIFICATION
                @if(\App\Models\User::where('kyc_status', 'pending')->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-[10px] px-1.5 rounded-full">!</span>
                @endif
            </a>

            <a href="{{ route('admin.ratings.index') }}" class="flex items-center gap-3 px-4 py-3 text-xs font-mono rounded hover:bg-slate-800 {{ request()->routeIs('admin.ratings.*') ? 'bg-slate-800 text-cyan-400 border-l-2 border-cyan-500' : 'text-slate-400 border-l-2 border-transparent' }}">
                <i data-lucide="star" class="w-4 h-4"></i>
                RATING_REVIEW
                @if(\App\Models\Rating::where('status', 'pending')->count() > 0)
                    <span class="ml-auto bg-yellow-500 text-black text-[10px] px-1.5 rounded-full font-bold">!</span>
                @endif
            </a>

            <div class="pt-4 pb-2 px-4 text-[10px] font-mono text-slate-600 uppercase">System</div>
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs font-mono rounded hover:bg-slate-800 text-slate-400 border-l-2 border-transparent">
                <i data-lucide="activity" class="w-4 h-4"></i>
                AUDIT_LOGS
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-xs font-mono rounded hover:bg-slate-800 text-slate-400 border-l-2 border-transparent">
                <i data-lucide="settings" class="w-4 h-4"></i>
                CONFIGURATION
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-slate-800 rounded flex items-center justify-center font-bold text-cyan-500 border border-slate-700">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-xs font-bold text-slate-200 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-slate-500 font-mono uppercase">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-900/20 border border-red-500/30 text-red-400 hover:bg-red-900/40 text-xs font-mono uppercase rounded transition-colors">
                    <i data-lucide="log-out" class="w-3 h-3"></i> Terminate
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
        <!-- Header -->
        <header class="h-16 border-b border-slate-800 bg-slate-900/50 backdrop-blur flex items-center justify-between px-6 shrink-0 z-10">
            <h1 class="text-lg font-mono font-bold text-slate-100">
                @yield('header', 'DASHBOARD')
            </h1>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-[10px] font-mono text-emerald-500 bg-emerald-950/30 px-2 py-1 border border-emerald-500/30 rounded">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    SYSTEM_SECURE
                </div>
            </div>
        </header>

        <!-- Content Scrollable -->
        <div class="flex-1 overflow-y-auto bg-slate-950 relative">
            <div class="absolute inset-0 pointer-events-none opacity-5 admin-grid z-0"></div>
            <div class="relative z-10 p-6">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-emerald-950/30 border border-emerald-500/50 text-emerald-400 text-sm font-mono flex items-center gap-2 rounded">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-950/30 border border-red-500/50 text-red-400 text-sm font-mono flex items-center gap-2 rounded">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
