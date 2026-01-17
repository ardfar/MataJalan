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
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #06b6d4; }
        
        /* Grid Background Pattern */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #0891b2 1px, transparent 1px), linear-gradient(to bottom, #0891b2 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-300 font-sans selection:bg-cyan-500/30 selection:text-cyan-200 min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Background Grid Effect -->
    <div class="fixed inset-0 pointer-events-none z-0 opacity-10 bg-grid-pattern"></div>

    <div class="relative z-10 flex flex-col min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-slate-900/50 backdrop-blur-sm border-b border-slate-800">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-mono font-bold text-xl text-slate-100 uppercase tracking-tight flex items-center gap-2">
                        <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span>
                        {{ $header }}
                    </h2>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
    
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
