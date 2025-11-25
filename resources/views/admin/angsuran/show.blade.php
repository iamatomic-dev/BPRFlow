<x-layouts.admin :title="'Kartu Angsuran'">
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.angsuran.index') }}" class="text-gray-500 hover:text-gray-700"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <h1 class="text-xl font-bold text-gray-800">
                Kartu Angsuran: {{ $application->nasabahProfile->nama_lengkap }}
                <span class="font-mono">({{ $application->no_perjanjian_kredit }})</span>
            </h1>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Ke</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4">Tagihan</th>
                        <th class="px-6 py-4">Tgl Bayar</th>
                        <th class="px-6 py-4">Jumlah Bayar</th>
                        <th class="px-6 py-4">Denda</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($payments as $pay)
                        <tr class="{{ $pay->status_pembayaran == 'Paid' ? 'bg-green-50' : 'hover:bg-gray-50' }}">
                            <td class="px-6 py-4 font-bold">{{ $pay->angsuran_ke }}</td>
                            <td class="px-6 py-4">{{ $pay->jatuh_tempo->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-semibold">Rp
                                {{ number_format($pay->jumlah_angsuran, 0, ',', '.') }}</td>

                            {{-- Data Realisasi --}}
                            <td class="px-6 py-4">{{ $pay->tanggal_bayar ? $pay->tanggal_bayar->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $pay->jumlah_bayar > 0 ? 'Rp ' . number_format($pay->jumlah_bayar) : '-' }}</td>
                            <td class="px-6 py-4 text-red-600">
                                {{ $pay->denda > 0 ? 'Rp ' . number_format($pay->denda) : '-' }}</td>

                            <td class="px-6 py-4">
                                @if ($pay->status_pembayaran == 'Paid')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">LUNAS</span>
                                @elseif($pay->status_pembayaran == 'Partial')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">NYICIL</span>
                                @else
                                    <span
                                        class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-1 rounded">BELUM</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if ($pay->status_pembayaran != 'Paid')
                                    <button
                                        onclick="openPaymentModal({{ $pay->id }}, {{ $pay->angsuran_ke }}, {{ $pay->jumlah_angsuran }})"
                                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                        Input Bayar
                                    </button>
                                @else
                                    <button disabled class="text-gray-400 cursor-not-allowed"><i
                                            class="fa-solid fa-check"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL INPUT PEMBAYARAN --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePaymentModal()"></div>

            <div
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6 relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Pembayaran Angsuran Ke-<span
                        id="modalAngsuranKe"></span></h3>

                {{-- Formnya dinamis action-nya via JS --}}
                <form id="paymentForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" id="modalJumlahBayar"
                                class="w-full border-gray-300 rounded-lg font-bold text-green-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Denda (Jika Telat)</label>
                            <input type="number" name="denda" value="0"
                                class="w-full border-gray-300 rounded-lg text-red-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea name="catatan" class="w-full border-gray-300 rounded-lg"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closePaymentModal()"
                            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openPaymentModal(id, ke, tagihan) {
                document.getElementById('paymentModal').classList.remove('hidden');
                document.getElementById('modalAngsuranKe').innerText = ke;
                document.getElementById('modalJumlahBayar').value = tagihan;

                // Set action form dinamis
                // Asumsi route: /admin/angsuran/bayar/{id}
                let url = "{{ route('admin.angsuran.update', ':id') }}";
                url = url.replace(':id', id);
                document.getElementById('paymentForm').action = url;
            }

            function closePaymentModal() {
                document.getElementById('paymentModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-layouts.admin>
