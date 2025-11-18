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
        Schema::create('credit_collaterals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_application_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_agunan', ['SHM', 'SHGB']);
            $table->string('nomor_sertifikat');
            $table->string('atas_nama');
            $table->date('masa_berlaku')->nullable(); // hanya untuk SHGB
            $table->string('foto_agunan')->nullable();
            $table->string('file_sertifikat')->nullable(); // upload SHM/SHGB
            $table->string('file_pbb')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_collaterals');
    }
};
