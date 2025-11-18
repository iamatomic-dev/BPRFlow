@props([
    'label' => 'Upload File',
    'name',
    'accept' => '',
    'note' => '',
    'required' => false,
])

{{-- Helper PHP untuk mendapatkan nilai 'old' yang benar --}}
@php
    // Fungsi str_replace akan menggantikan '[' dengan '.' dan ']' dengan '.'
    $fieldNameForOld = str_replace(['[', ']'], ['.', ''], $name);
    $oldValue = old($fieldNameForOld);
@endphp

<div x-data="fileUploader(
    '{{ $name }}',
    {{ $required ? 'true' : 'false' }},
    {{ json_encode($oldValue) }}

)" class="w-full">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <!-- INPUT FILE -->
    <input type="file" accept="{{ $accept }}" @change="uploadFile($event)"
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4
            file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold
            file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition">

    <!-- HIDDEN INPUT -->
    <input type="hidden" name="{{ $name }}" x-model="savedPath" value="{{ $oldValue }}">

    <!-- VALIDASI -->
    <p x-show="required && !savedPath" class="text-xs text-red-500 mt-1">
        File ini wajib diupload.
    </p>

    <!-- STATUS -->
    <p x-show="uploading" class="text-xs text-blue-600 mt-2">Mengupload...</p>
    <p x-show="savedPath && !uploading" class="text-xs text-green-600 mt-2">✔ Tersimpan</p>

    <p class="text-xs text-gray-500 mt-1">{{ $note }}</p>
</div>

@push('scripts')
    <script>
        function fileUploader(fieldName, required, initialValue = '') {
            return {
                required: required,
                uploading: false,
                savedPath: initialValue, // ← restore hasil upload temp

                async uploadFile(e) {
                    if (!e.target.files[0]) return;

                    this.uploading = true;

                    let file = e.target.files[0];
                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('field', fieldName);
                    formData.append('_token', '{{ csrf_token() }}');

                    let response = await fetch('{{ route('pengajuan.upload.temp') }}', {
                        method: 'POST',
                        body: formData
                    });

                    let result = await response.json();
                    this.savedPath = result.path;
                    this.uploading = false;
                }
            }
        }

        // Disable input tersembunyi agar tidak memicu error invalid form control
        document.addEventListener('submit', function() {
            document.querySelectorAll('[x-show]').forEach(el => {
                if (el.offsetParent === null) {
                    el.querySelectorAll('input, select').forEach(i => i.disabled = true);
                }
            });
        });
    </script>
@endpush
