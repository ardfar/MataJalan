<x-guest-layout>
    @section('title', 'Reset Password | MATAJALAN_OS')
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold font-mono text-slate-100 tracking-tight flex items-center justify-center gap-2">
            <i data-lucide="key-round" class="w-5 h-5 text-cyan-500"></i>
            RESET_CREDENTIALS
        </h2>
        <p class="text-xs text-slate-500 font-mono mt-2 leading-relaxed">
            {{ __('CREATE NEW SECURE ACCESS KEY') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-1">{{ __('Email Address') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="mail" class="h-4 w-4 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                </div>
                <input id="email" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
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
                <input id="password" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="password" name="password" required autocomplete="new-password" placeholder="NEW PASSWORD" />
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
                <input id="password_confirmation" class="block w-full pl-10 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="CONFIRM NEW PASSWORD" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs font-mono" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-cyan-900/20 border border-cyan-500/50 hover:bg-cyan-500 hover:text-white text-cyan-400 font-mono font-bold text-sm uppercase tracking-wider transition-all group">
                <i data-lucide="save" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                {{ __('RESET_PASSWORD') }}
            </button>
        </div>
    </form>
</x-guest-layout>
