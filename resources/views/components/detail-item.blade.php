@props(['label', 'value'])

<div {{ $attributes->merge(['class' => 'border-b border-gray-50 pb-1 last:border-0']) }}>
    <p class="text-xs text-gray-500 uppercase font-semibold">{{ $label }}</p>
    <p class="text-sm font-medium text-gray-900">{{ $value ?? '-' }}</p>
</div>