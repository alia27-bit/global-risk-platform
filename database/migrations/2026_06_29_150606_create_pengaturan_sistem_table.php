<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistem', function (Blueprint $table) {

            $table->id();

            $table->string('nama_pengaturan');

            $table->text('nilai_pengaturan');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};