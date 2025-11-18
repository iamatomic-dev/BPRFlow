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
        Schema::table('credit_collaterals', function (Blueprint $table) {
            $table->dropColumn('file_pbb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_collaterals', function (Blueprint $table) {
            $table->string('file_pbb', 255)->nullable();
        });
    }
};
