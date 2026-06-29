<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_api', function (Blueprint $table) {

            $table->id();

            $table->string('nama_api');

            $table->string('endpoint');

            $table->integer('status_kode');

            $table->integer('durasi_ms');

            $table->text('pesan')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_api');
    }
};