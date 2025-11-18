<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_facility_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_facility_id')->constrained()->onDelete('cascade');

            $table->decimal('min_plafond', 15, 2)->nullable();
            $table->decimal('max_plafond', 15, 2)->nullable();

            $table->decimal('bunga', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_facility_tiers');
    }
};

