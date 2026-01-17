@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-cyan-500 text-start text-base font-mono font-bold text-cyan-400 bg-cyan-900/20 focus:outline-none focus:text-cyan-500 focus:bg-cyan-900/30 focus:border-cyan-700 transition duration-150 ease-in-out uppercase'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-mono font-medium text-slate-400 hover:text-cyan-400 hover:bg-slate-800 hover:border-slate-700 focus:outline-none focus:text-cyan-400 focus:bg-slate-800 focus:border-slate-700 transition duration-150 ease-in-out uppercase';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
