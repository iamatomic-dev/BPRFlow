@php
    // 1. DYNAMIC LAYOUT & DISPLAY LOGIC
    $user = Auth::user();

    // Tentukan layout berdasarkan role
    if ($user->hasRole('Direktur')) {
        $layout = 'layouts.direktur';
        $displayAdminAction = 'display: none;'; // Hide Admin Action column
        $displayDirekturAction = 'display: table-cell;'; // Show Direktur Action column
        $back_link = route('direktur.angsuran.index');
    } elseif ($user->hasRole('Manager')) {
        $layout = 'layouts.manager';
        $displayAdminAction = 'display: none;'; // Hide Admin Action
        $displayDirekturAction = 'display: none;'; // Hide Direktur Action
        $back_link = route('manager.angsuran.index');
    } else {
        // Admin
        $layout = 'layouts.admin';
        $displayAdminAction = 'display: table-cell;'; // Show Admin Action column
        $displayDirekturAction = 'display: none;'; // Hide Direktur Action
        $back_link = route('admin.angsuran.index');
    }

    // Tentukan apakah user saat ini adalah Direktur/Admin
    $isDirektur = $user->hasRole('Direktur');
    $isAdmin = $user->hasRole('Admin');
@endphp

<x-dynamic-component :component="$layout" :title="'Kartu Angsuran'">
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ $back_link }}" class="text-gray-500 hover:text-gray-700"><i
                    class="fa-solid fa-arrow-left"></i></a>
            <h1 class="text-xl font-bold text-gray-800">
                Kartu Angsuran: {{ $application->nasabahProfile->nama_lengkap }}
                <span class="font-mono text-xs text-gray-500">({{ $application->no_perjanjian_kredit }})</span>
            </h1>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-2">Ke</th>
                        <th class="px-4 py-2">Jatuh Tempo</th>
                        <th class="px-4 py-2">Tagihan</th>
                        <th class="px-4 py-2">Tgl Bayar</th>
                        <th class="px-4 py-2">Jumlah Bayar</th>
                        <th class="px-4 py-2">Denda</th>
                        <th class="px-4 py-2">Teller Notes</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Reversal Info</th>
                        <th class="px-4 py-2 text-center" style='{{ $displayAdminAction }}'>Aksi</th>
                        <th class="px-4 py-2 text-center" style='{{ $displayDirekturAction }}'>Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($payments as $pay)
                        @php
                            // Tentukan warna baris berdasarkan status
                            $rowClass = $pay->reversal_date
                                ? 'bg-red-50 italic'
                                : ($pay->status_pembayaran == 'Paid'
                                    ? 'bg-green-50'
                                    : 'hover:bg-gray-50');
                        @endphp

                        <tr class="{{ $rowClass }}">
                            <td class="px-4 py-2 font-bold">{{ $pay->angsuran_ke }}</td>
                            <td class="px-4 py-2">{{ $pay->jatuh_tempo->format('d M Y') }}</td>
                            <td class="px-4 py-2 font-semibold">Rp
                                {{ number_format($pay->jumlah_angsuran, 0, ',', '.') }}</td>

                            {{-- Data Realisasi --}}
                            <td class="px-4 py-2">{{ $pay->tanggal_bayar ? $pay->tanggal_bayar->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $pay->jumlah_bayar > 0 ? 'Rp ' . number_format($pay->jumlah_bayar) : '-' }}</td>
                            <td class="px-4 py-2 text-red-600">
                                {{ $pay->denda > 0 ? 'Rp ' . number_format($pay->denda) : '-' }}</td>
                            
                            <td class="px-4 py-2 text-xs">
                                @if ($pay->catatan_teller)
                                    <div class="text-[10px] text-gray-500 truncate">
                                        {{ $pay->catatan_teller }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                @if ($pay->reversal_date)
                                    <span
                                        class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">DIBATALKAN</span>
                                @elseif($pay->status_pembayaran == 'Paid')
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

                            {{-- Kolom Reversal Info --}}
                            <td class="px-4 py-2 text-xs">
                                @if ($pay->reversal_date)
                                    <span
                                        class="text-red-700 font-medium">({{ $pay->reversal_date->format('d/m/y') }})</span>
                                    <div class="text-[10px] text-gray-500 truncate">
                                        Oleh : {{ $pay->reverser->name ?? 'System' }} <br>
                                        Note : {{ $pay->reversal_note }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>


                            {{-- KOLOM AKSI ADMIN --}}
                            <td class="px-4 py-2 text-center" style='{{ $displayAdminAction }}'>
                                @if ($pay->status_pembayaran != 'Paid')
                                    <button
                                        onclick="openPaymentModal({{ $pay->id }}, {{ $pay->angsuran_ke }}, {{ $pay->jumlah_angsuran }})"
                                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                        Input Bayar
                                    </button>
                                @else
                                    <button disabled class="text-gray-400 cursor-not-allowed text-xs">Selesai</button>
                                @endif
                            </td>

                            {{-- KOLOM AKSI DIREKTUR --}}
                            <td class="px-4 py-2 text-center" style='{{ $displayDirekturAction }}'>
                                @if (!$pay->reversal_date && ($pay->status_pembayaran == 'Paid' || $pay->status_pembayaran == 'Partial'))
                                    <button onclick="openReversalModal({{ $pay->id }})"
                                        class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                        Reversal
                                    </button>
                                @elseif($pay->reversal_date)
                                    <button disabled class="text-xs text-red-700 bg-red-100 px-3 py-1 rounded">Sudah
                                        Dibatalkan</button>
                                @else
                                    <button disabled class="text-gray-400 cursor-not-allowed text-xs">Belum
                                        Dibayar</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL INPUT PEMBAYARAN (ADMIN) --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closePaymentModal()"></div>

            <div
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6 relative z-10">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Pembayaran Angsuran Ke-<span
                        id="modalAngsuranKe"></span></h3>

                <form id="paymentForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" id="modalJumlahBayar" required
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


    {{-- MODAL REVERSAL (DIREKTUR) --}}
    @if ($isDirektur)
        <div id="reversalModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeReversalModal()">
                </div>

                <div
                    class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6 relative z-10">
                    <h3 class="text-lg font-bold text-red-600 mb-4 border-b pb-2">Konfirmasi Pembatalan Transaksi</h3>

                    <form id="reversalForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <p class="text-sm text-gray-700 mb-4">Anda akan membatalkan transaksi ini. Status akan
                                di-reset menjadi BELUM BAYAR.</p>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Reversal</label>
                                <input type="date" name="reversal_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan Reversal (Wajib)</label>
                                <textarea name="reversal_note" rows="3" required
                                    placeholder="Jelaskan alasan pembatalan (Contoh: Salah input nominal / Transfer fiktif)"
                                    class="w-full border-gray-300 rounded-lg"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="submit" onclick="closeReversalModal()"
                                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">Tutup</button>
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Ya, Batalkan
                                Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif


    @push('scripts')
        <script>
            // Set up the base URL for the Reversal route (since the controller is DirekturAngsuranController)
            const REVERSAL_URL = "{{ route('direktur.angsuran.reverse', ['paymentId' => '__ID__']) }}";
            const ADMIN_UPDATE_URL = "{{ route('admin.angsuran.update', ['paymentId' => '__ID__']) }}";

            // JS untuk Modal Payment (ADMIN)
            function openPaymentModal(id, ke, tagihan) {
                document.getElementById('paymentModal').classList.remove('hidden');
                document.getElementById('modalAngsuranKe').innerText = ke;
                document.getElementById('modalJumlahBayar').value = tagihan;

                // Set action form dinamis untuk Admin
                let url = ADMIN_UPDATE_URL.replace('__ID__', id);
                document.getElementById('paymentForm').action = url;
            }

            function closePaymentModal() {
                document.getElementById('paymentModal').classList.add('hidden');
            }

            // JS untuk Modal Reversal (DIREKTUR)
            @if ($isDirektur)
                function openReversalModal(paymentId) {
                    document.getElementById('reversalModal').classList.remove('hidden');

                    // Set action form dinamis untuk Direktur
                    let url = REVERSAL_URL.replace('__ID__', paymentId);
                    document.getElementById('reversalForm').action = url;
                }

                function closeReversalModal() {
                    document.getElementById('reversalModal').classList.add('hidden');
                }
            @endif
        </script>
    @endpush
</x-dynamic-component>
