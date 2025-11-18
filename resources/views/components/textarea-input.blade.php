@props([
    'id' => null,
    'name',
    'label' => null,
    'value' => '',
    'rows' => 4,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'placeholder' => '',
])

<div>
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{-- {{ $required ? 'required' : '' }} --}}
        {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500']) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
