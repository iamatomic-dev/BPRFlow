@props([
    'id' => null,
    'name',
    'type' => 'text',
    'label' => null,
    'value' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'placeholder' => '',
])

<div>
    @if ($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input id="{{ $id ?? $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
        {{-- {{ $required ? 'required' : '' }} --}} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' =>
                'w-full rounded-lg shadow-sm border ' .
                ($readonly
                    ? 'bg-gray-100 text-gray-600 border-gray-300 cursor-not-allowed'
                    : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'),
        ]) }} />

    @error($name)
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
