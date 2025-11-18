<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_application_id')->constrained()->onDelete('cascade');

            $table->string('nama_file');
            $table->string('path');
            $table->string('jenis_dokumen'); // contoh: KTP, Slip Gaji, Surat Nikah
            $table->enum('status_verifikasi', ['Belum Diverifikasi', 'Valid', 'Tidak Valid'])->default('Belum Diverifikasi');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_documents');
    }
};
