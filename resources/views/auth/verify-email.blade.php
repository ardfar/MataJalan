<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold font-mono text-slate-100 tracking-tight flex items-center justify-center gap-2">
            <i data-lucide="mail-check" class="w-5 h-5 text-cyan-500"></i>
            EMAIL_VERIFICATION
        </h2>
        <p class="text-xs text-slate-500 font-mono mt-2 leading-relaxed">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-emerald-950/30 border border-emerald-500/50 rounded text-center">
            <p class="text-xs font-mono font-bold text-emerald-400">
                {{ __('VERIFICATION_LINK_SENT') }}
            </p>
            <p class="text-[10px] text-emerald-600 font-mono mt-1">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-cyan-900/20 border border-cyan-500/50 hover:bg-cyan-500 hover:text-white text-cyan-400 font-mono font-bold text-sm uppercase tracking-wider transition-all group">
                <i data-lucide="send" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                {{ __('RESEND_VERIFICATION_EMAIL') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf

            <button type="submit" class="text-xs font-mono text-slate-500 hover:text-cyan-400 underline decoration-slate-700 hover:decoration-cyan-500 transition-all">
                {{ __('TERMINATE_SESSION (LOGOUT)') }}
            </button>
        </form>
    </div>
</x-guest-layout>
