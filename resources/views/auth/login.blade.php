<x-guest-layout>
    @section('title', 'Login | MATAJALAN_OS')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold font-mono text-slate-100 tracking-tight flex items-center justify-center gap-2">
            <i data-lucide="lock" class="w-6 h-6 text-cyan-500"></i>
            ACCESS_CONTROL
        </h2>
        <p class="text-xs text-slate-500 font-mono mt-1">PLEASE AUTHENTICATE TO PROCEED</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Email Address') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="email" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="AGENT@MATAJALAN.OS" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

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
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative">
                    <input id="remember_me" type="checkbox" class="sr-only peer" name="remember">
                    <div class="w-4 h-4 border border-slate-600 bg-slate-950 peer-checked:bg-cyan-600 peer-checked:border-cyan-500 transition-all"></div>
                    <i data-lucide="check" class="w-3 h-3 text-white absolute top-0.5 left-0.5 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                </div>
                <span class="ms-2 text-xs font-mono text-slate-500 group-hover:text-cyan-400 transition-colors">{{ __('REMEMBER_SESSION') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-mono text-slate-500 hover:text-cyan-400 underline decoration-slate-700 hover:decoration-cyan-500 transition-all" href="{{ route('password.request') }}">
                    {{ __('RESET_CREDENTIALS?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-cyan-900/20 border border-cyan-500/50 hover:bg-cyan-500 hover:text-white text-cyan-400 font-mono font-bold text-sm uppercase tracking-wider transition-all group">
                <i data-lucide="log-in" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                {{ __('INITIATE_LOGIN') }}
            </button>
        </div>
        
        <div class="pt-6 mt-6 border-t border-slate-800 text-center">
             <p class="text-[10px] text-slate-600 font-mono">
                UNAUTHORIZED ACCESS IS A CRIMINAL OFFENSE UNDER<br>CYBERCRIME ACT 2077, SECTION 9.
            </p>
        </div>
    </form>
</x-guest-layout>
