<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasabah_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('no_ktp')->unique();
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->string('alamat_tinggal');
            $table->string('alamat_ktp');
            $table->string('pendidikan_terakhir');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('no_npwp')->nullable();
            $table->string('status_rumah');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabah_profiles');
    }
};
