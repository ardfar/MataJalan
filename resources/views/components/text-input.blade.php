@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full pl-3 pr-3 py-2 border border-slate-700 rounded-none bg-slate-950 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 font-mono transition-all sm:text-sm shadow-sm']) }}>
