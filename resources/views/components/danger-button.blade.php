<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-red-900/20 border border-red-500/50 rounded-sm font-mono font-bold text-xs text-red-400 uppercase tracking-widest hover:bg-red-500 hover:text-white focus:bg-red-900 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
