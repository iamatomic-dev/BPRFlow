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
        Schema::table('credit_payments', function (Blueprint $table) {
            $table->timestamp('reversal_date')->nullable()->after('status_pembayaran');
            $table->text('reversal_note')->nullable()->after('reversal_date');
            $table->foreignId('reversal_user_id')->nullable()->constrained('users')->after('reversal_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_payments', function (Blueprint $table) {
            $table->dropForeign(['reversal_user_id']);
            $table->dropColumn(['reversal_date', 'reversal_note', 'reversal_user_id']);
        });
    }
};
