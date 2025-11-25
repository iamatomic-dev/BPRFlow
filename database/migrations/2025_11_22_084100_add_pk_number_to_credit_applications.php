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
            $table->string('no_perjanjian_kredit', 50)->nullable()->unique()->after('no_pengajuan');
            $table->date('tgl_akad')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            $table->dropColumn([
                'no_perjanjian_kredit',
                'tgl_akad'
            ]);
        });
    }
};
