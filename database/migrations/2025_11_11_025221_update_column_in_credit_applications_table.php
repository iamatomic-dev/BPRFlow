<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('credit_facility_id')->nullable()->change();
            $table->string('tujuan_pinjaman')->nullable()->change();
            $table->decimal('jumlah_pinjaman', 15, 2)->default(0.00)->change();
            $table->integer('jangka_waktu')->default(0)->change();
            $table->string('sumber_pendapatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('credit_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('credit_facility_id')->nullable(false)->change();
            $table->string('tujuan_pinjaman');
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->integer('jangka_waktu'); 
            $table->string('sumber_pendapatan');
        });
    }
};
