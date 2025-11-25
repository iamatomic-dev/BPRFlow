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
        Schema::table('credit_applications', function (Blueprint $table) {
            // Menyimpan path file PDF SLIK
            $table->string('slik_path')->nullable()->after('submitted_at');
            // Menyimpan status kolektibilitas (misal: 'Lancar', 'Dalam Perhatian Khusus', 'Macet')
            $table->string('slik_status')->nullable()->after('slik_path');
            // Catatan tambahan admin tentang SLIK nasabah
            $table->text('slik_notes')->nullable()->after('slik_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            $table->dropColumn(['slik_path', 'slik_status', 'slik_notes']);
        });
    }
};
