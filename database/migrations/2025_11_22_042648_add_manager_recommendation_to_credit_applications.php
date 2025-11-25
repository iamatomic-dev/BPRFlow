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
            // Kolom Rekomendasi Manager
            $table->foreignId('manager_id')->nullable()->constrained('users'); // Siapa managernya
            $table->dateTime('managed_at')->nullable(); // Kapan direview

            $table->string('recommendation_status')->nullable(); // 'Rekomendasi Disetujui' atau 'Rekomendasi Ditolak'
            $table->decimal('recommended_amount', 15, 2)->nullable(); // Plafond hasil rekomendasi (bisa beda dengan pengajuan)
            $table->integer('recommended_tenor')->nullable(); // Tenor hasil rekomendasi
            $table->text('manager_note')->nullable(); // Catatan analisa (SWOT / 5C)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'manager_id',
                'managed_at',
                'recommendation_status',
                'recommended_amount',
                'recommended_tenor',
                'manager_note'
            ]);
        });
    }
};
