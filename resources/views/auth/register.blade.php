<x-guest-layout>
    @section('title', 'Register | MATAJALAN_OS')
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold font-mono text-slate-100 tracking-tight flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-6 h-6 text-cyan-500"></i>
            NEW_AGENT_REGISTRATION
        </h2>
        <p class="text-xs text-slate-500 font-mono mt-1">CREATE CREDENTIALS FOR ACCESS</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Full Name') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="user" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="name" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="OFFICER NAME" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Email Address') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="email" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="AGENT@MATAJALAN.OS" />
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
                                required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Confirm Password') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="check-circle" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="password_confirmation" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="CONFIRM ••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="text-xs font-mono text-slate-500 hover:text-cyan-400 underline decoration-slate-700 hover:decoration-cyan-500 transition-all" href="{{ route('login') }}">
                {{ __('ALREADY_REGISTERED?') }}
            </a>

            <button type="submit" class="flex items-center justify-center gap-2 px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-mono font-bold text-sm uppercase tracking-wider transition-all shadow-lg shadow-cyan-900/20">
                {{ __('REGISTER_AGENT') }}
            </button>
        </div>
    </form>
</x-guest-layout>
