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
        Schema::table('nasabah_profiles', function (Blueprint $table) {
            $table->string('jenis_kelamin')->after('nama_lengkap');
            $table->string('nama_ibu_kandung')->after('agama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nasabah_profiles', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('nama_ibu_kandung');
        });
    }
};
