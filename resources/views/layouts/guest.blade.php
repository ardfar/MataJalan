<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #020617; 
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b; 
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #06b6d4; 
        }
        
        /* Grid Background Pattern */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #0891b2 1px, transparent 1px), linear-gradient(to bottom, #0891b2 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-300 font-sans selection:bg-cyan-500/30 selection:text-cyan-200 h-screen flex flex-col relative overflow-hidden">

    <!-- Background Grid Effect -->
    <div class="fixed inset-0 pointer-events-none z-0 opacity-10 bg-grid-pattern"></div>

    <!-- HEADER -->
    <header class="z-20 border-b border-slate-800 bg-slate-900/90 backdrop-blur-md h-16 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-4">
            <a href="/" class="flex items-center gap-4">
                <div class="w-8 h-8 bg-cyan-900/20 border border-cyan-500 flex items-center justify-center animate-pulse">
                    <i data-lucide="eye" class="w-5 h-5 text-cyan-400"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-100 tracking-wider">MATA<span class="text-cyan-500">JALAN</span>_OS</h1>
                    <p class="text-[10px] font-mono text-cyan-700">SURVEILLANCE & RATING SYSTEM v2.0</p>
                </div>
            </a>
        </div>

        <!-- System Status -->
        <div class="flex items-center gap-6 font-mono text-xs">
            <div class="hidden lg:flex flex-col items-end">
                <span class="text-slate-500">SYSTEM_TIME</span>
                <span id="clock" class="text-cyan-400">00:00:00</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></div>
                <span class="text-emerald-500">ONLINE</span>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex items-center justify-center relative z-10 p-6">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-2xl relative overflow-hidden">
             <!-- Corner Accents -->
            <div class="absolute top-0 left-0 w-2 h-2 border-l-2 border-t-2 border-cyan-500"></div>
            <div class="absolute top-0 right-0 w-2 h-2 border-r-2 border-t-2 border-cyan-500"></div>
            <div class="absolute bottom-0 left-0 w-2 h-2 border-l-2 border-b-2 border-cyan-500"></div>
            <div class="absolute bottom-0 right-0 w-2 h-2 border-r-2 border-b-2 border-cyan-500"></div>

            {{ $slot }}
        </div>
    </main>

    <script>
        lucide.createIcons();
        
        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('clock');
            if(clock) clock.innerText = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
