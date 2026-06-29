<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbandingan_negara', function (Blueprint $table) {

            $table->id();

            $table->foreignId('negara_pertama_id')
                ->constrained('negara')
                ->cascadeOnDelete();

            $table->foreignId('negara_kedua_id')
                ->constrained('negara')
                ->cascadeOnDelete();

            $table->decimal('skor_negara_pertama',5,2);

            $table->decimal('skor_negara_kedua',5,2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbandingan_negara');
    }
};