<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_application_id')->constrained()->onDelete('cascade');

            // Informasi Jadwal
            $table->integer('angsuran_ke'); // 1, 2, 3 ... dst
            $table->date('jatuh_tempo'); // Tanggal wajib bayar (misal tgl 5 setiap bulan)

            // Rincian Tagihan (Disimpan agar tidak perlu hitung ulang & konsisten)
            $table->decimal('tagihan_pokok', 15, 2);
            $table->decimal('tagihan_bunga', 15, 2);
            $table->decimal('jumlah_angsuran', 15, 2); // Pokok + Bunga

            // Informasi Pembayaran Realisasi
            $table->decimal('denda', 15, 2)->default(0); // Jika telat
            $table->decimal('jumlah_bayar', 15, 2)->default(0); // Yang dibayar nasabah
            $table->timestamp('tanggal_bayar')->nullable(); // Kapan nasabah bayar
            $table->string('bukti_bayar')->nullable(); // Path foto struk (jika transfer)

            // Status Per Bulan
            // Unpaid (Belum), Paid (Lunas), Partial (Nyicil), Late (Telat)
            $table->string('status_pembayaran')->default('Unpaid');

            $table->text('catatan_teller')->nullable(); // Jika bayar cash
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_payments');
    }
};
