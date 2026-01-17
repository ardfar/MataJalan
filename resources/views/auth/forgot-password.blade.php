<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold font-mono text-slate-100 tracking-tight flex items-center justify-center gap-2">
            <i data-lucide="refresh-cw" class="w-5 h-5 text-cyan-500"></i>
            CREDENTIAL_RECOVERY
        </h2>
        <p class="text-xs text-slate-500 font-mono mt-2 leading-relaxed">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Email Address') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="email" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="AGENT@MATAJALAN.OS" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-cyan-900/20 border border-cyan-500/50 hover:bg-cyan-500 hover:text-white text-cyan-400 font-mono font-bold text-sm uppercase tracking-wider transition-all group">
                <i data-lucide="send" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                {{ __('SEND_RESET_LINK') }}
            </button>
        </div>
    </form>
</x-guest-layout>
