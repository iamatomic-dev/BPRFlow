<x-layouts.nasabah>
    <x-slot name="title">Review Pengajuan Kredit</x-slot>

    <h2 class="text-xl font-semibold mb-4">Data Diri</h2>
    <p>{{ $profile->nama_lengkap }} ({{ $profile->no_ktp }})</p>
    <p>{{ $profile->email }} | {{ $profile->no_hp }}</p>

    <h2 class="text-xl font-semibold mt-6 mb-4">Data Pengajuan</h2>
    <p>Fasilitas: {{ $application->facility->nama_fasilitas }}</p>
    <p>Jumlah: Rp {{ number_format($application->jumlah_pinjaman) }}</p>
    <p>Jangka waktu: {{ $application->jangka_waktu }} bulan</p>
    <p>Tujuan: {{ $application->tujuan_pinjaman }}</p>

    <form method="POST" action="{{ route('pengajuan.submit') }}">
        @csrf
        <button type="submit" class="mt-6 px-6 py-2 bg-green-600 text-white rounded-lg">Kirim Pengajuan</button>
    </form>
</x-layouts.nasabah>
