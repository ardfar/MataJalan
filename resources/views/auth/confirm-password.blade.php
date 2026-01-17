<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold font-mono text-slate-100 tracking-tight flex items-center justify-center gap-2">
            <i data-lucide="shield-alert" class="w-5 h-5 text-red-500"></i>
            SECURE_AREA_ACCESS
        </h2>
        <p class="text-xs text-slate-500 font-mono mt-2 leading-relaxed">
            {{ __('CONFIRM CREDENTIALS TO PROCEED') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Password') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="key" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="password" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="CONFIRM PASSWORD" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-900/20 border border-red-500/50 hover:bg-red-500 hover:text-white text-red-400 font-mono font-bold text-sm uppercase tracking-wider transition-all group">
                <i data-lucide="lock-open" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                {{ __('CONFIRM_ACCESS') }}
            </button>
        </div>
    </form>
</x-guest-layout>
