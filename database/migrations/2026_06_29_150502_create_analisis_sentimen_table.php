<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis_sentimen', function (Blueprint $table) {

            $table->id();

            $table->foreignId('berita_id')
                ->constrained('berita')
                ->cascadeOnDelete();

            $table->integer('skor_positif')->default(0);

            $table->integer('skor_negatif')->default(0);

            $table->integer('skor_netral')->default(0);

            $table->enum('hasil_sentimen',[
                'Positif',
                'Netral',
                'Negatif'
            ]);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_sentimen');
    }
};