<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-cyan-900/20 border border-cyan-500/50 rounded-sm font-mono font-bold text-xs text-cyan-400 uppercase tracking-widest hover:bg-cyan-500 hover:text-white focus:bg-cyan-900 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
