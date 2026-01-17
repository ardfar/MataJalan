<x-app-layout>
    <x-slot name="header">
        {{ __('DASHBOARD') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 overflow-hidden shadow-sm sm:rounded-lg relative group">
                <!-- Tech decoration -->
                <div class="absolute top-0 right-0 p-2 opacity-50">
                    <div class="flex gap-1">
                        <div class="w-1 h-1 bg-cyan-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-cyan-500 rounded-full"></div>
                        <div class="w-1 h-1 bg-cyan-500 rounded-full"></div>
                    </div>
                </div>
                
                <div class="p-6 text-slate-300 font-mono">
                    <div class="flex items-center gap-3 mb-4">
                        <i data-lucide="terminal" class="text-cyan-500"></i>
                        <h3 class="text-lg font-bold text-slate-100">SYSTEM_MESSAGE</h3>
                    </div>
                    <p class="text-emerald-400 mb-2">> AUTHENTICATION_SUCCESSFUL</p>
                    <p class="mb-4">> WELCOME_AGENT: <span class="text-cyan-400">{{ Auth::user()->name }}</span></p>
                    <p class="text-slate-500 text-sm">Session ID: {{ Str::uuid() }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Quick Actions -->
                <a href="{{ route('home') }}" class="bg-slate-900 border border-slate-800 p-6 rounded hover:border-cyan-500/50 transition-colors group">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-slate-100 font-bold font-mono">SURVEILLANCE</h4>
                        <i data-lucide="eye" class="text-slate-500 group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">Access main surveillance grid and live feeds.</p>
                </a>

                <a href="{{ route('kyc.index') }}" class="bg-slate-900 border border-slate-800 p-6 rounded hover:border-cyan-500/50 transition-colors group">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-slate-100 font-bold font-mono">VERIFICATION</h4>
                        <i data-lucide="shield-check" class="text-slate-500 group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">Manage identity verification status.</p>
                </a>

                <a href="{{ route('profile.edit') }}" class="bg-slate-900 border border-slate-800 p-6 rounded hover:border-cyan-500/50 transition-colors group">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-slate-100 font-bold font-mono">SETTINGS</h4>
                        <i data-lucide="settings" class="text-slate-500 group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <p class="text-xs text-slate-500 font-mono">Configure system preferences and credentials.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
