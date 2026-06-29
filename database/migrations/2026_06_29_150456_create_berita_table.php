<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {

            $table->id();

            $table->foreignId('negara_id')
                ->constrained('negara')
                ->cascadeOnDelete();

            $table->string('judul');

            $table->text('isi');

            $table->string('sumber');

            $table->string('url');

            $table->dateTime('tanggal_terbit');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};