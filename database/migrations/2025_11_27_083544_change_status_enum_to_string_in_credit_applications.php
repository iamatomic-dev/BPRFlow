<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE credit_applications MODIFY COLUMN status VARCHAR(255) DEFAULT 'draft_step1'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE credit_applications MODIFY COLUMN status ENUM(
            'draft_step1', 
            'draft_step2', 
            'draft_step3', 
            'Menunggu Verifikasi', 
            'Disetujui', 
            'Ditolak', 
            'Lunas'
        ) DEFAULT 'draft_step1'");
    }
};
