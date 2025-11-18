<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_application_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_application_id')->constrained()->onDelete('cascade');

            // Data pasangan
            $table->string('pasangan_nama')->nullable();
            $table->string('pasangan_no_ktp')->nullable();
            $table->string('pasangan_alamat_tinggal')->nullable();
            $table->string('pasangan_alamat_ktp')->nullable();
            $table->string('pasangan_pekerjaan')->nullable();
            $table->string('pasangan_email')->nullable();

            // Data penjamin
            $table->string('penjamin_nama')->nullable();
            $table->string('penjamin_no_ktp')->nullable();
            $table->string('penjamin_hubungan')->nullable();
            $table->string('penjamin_alamat')->nullable();
            $table->string('penjamin_email')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_application_details');
    }
};
