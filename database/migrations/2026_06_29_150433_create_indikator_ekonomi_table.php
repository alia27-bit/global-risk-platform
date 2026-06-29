<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikator_ekonomi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('negara_id')
                  ->constrained('negara')
                  ->cascadeOnDelete();

            $table->decimal('gdp',20,2)->nullable();

            $table->decimal('inflasi',8,2)->nullable();

            $table->decimal('ekspor',20,2)->nullable();

            $table->decimal('impor',20,2)->nullable();

            $table->year('tahun');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikator_ekonomi');
    }
};