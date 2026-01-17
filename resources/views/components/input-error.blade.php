@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-xs text-red-500 font-mono space-y-1 mt-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1">
                <span class="text-red-600">></span> {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
