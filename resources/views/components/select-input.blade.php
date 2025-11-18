@props([
    'id' => null,
    'name',
    'label' => null,
    'options' => [],
    'value' => '',
    'required' => false,
    'disabled' => false,
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

    <select id="{{ $id ?? $name }}" name="{{ $name }}" {{-- {{ $required ? 'required' : '' }} --}} {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500']) }}>
        <option value="">-- Pilih {{ strtolower($label ?? $name) }} --</option>
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected((string) old($name, $value ?? '') === (string) $key)>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
