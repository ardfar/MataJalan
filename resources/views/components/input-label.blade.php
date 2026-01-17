@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-mono font-bold text-xs text-cyan-700 uppercase tracking-wide']) }}>
    {{ $value ?? $slot }}
</label>
