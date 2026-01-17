@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-mono text-xs font-bold text-emerald-400 bg-emerald-950/30 border border-emerald-500/50 p-3 rounded mb-4 flex items-center gap-2']) }}>
        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
        {{ $status }}
    </div>
@endif
