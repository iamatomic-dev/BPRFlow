@props(['type' => 'info'])

@php
    $colors = [
        'info' => 'bg-blue-100 border-blue-500 text-blue-800',
        'success' => 'bg-green-100 border-green-500 text-green-800',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-800',
        'error' => 'bg-red-100 border-red-500 text-red-800',
    ];
@endphp

<div x-data="{ open: true }" x-show="open"
    {{ $attributes->merge(['class' => "relative mb-4 p-4 border-l-4 rounded-lg {$colors[$type]}"]) }}>
    <button type="button" @click="open = false"
        class="absolute top-5 right-3 text-lg font-bold leading-none text-gray-600 hover:text-gray-900 focus:outline-none">
        &times;
    </button>

    {{ $slot }}
</div>
