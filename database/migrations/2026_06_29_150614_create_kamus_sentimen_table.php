<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamus_sentimen', function (Blueprint $table) {

            $table->id();

            $table->string('kata');

            $table->enum('kategori',[
                'Positif',
                'Negatif'
            ]);

            $table->integer('bobot')->default(1);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamus_sentimen');
  }
};