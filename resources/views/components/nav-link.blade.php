@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-cyan-500 text-sm font-mono font-bold leading-5 text-cyan-400 focus:outline-none focus:border-cyan-700 transition duration-150 ease-in-out uppercase tracking-wider'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-mono font-medium leading-5 text-slate-400 hover:text-cyan-400 hover:border-slate-700 focus:outline-none focus:text-cyan-400 focus:border-slate-700 transition duration-150 ease-in-out uppercase tracking-wider';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
