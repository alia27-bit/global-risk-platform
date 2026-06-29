<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negara', function (Blueprint $table) {

            $table->id();

            $table->string('kode_negara',10)->unique();

            $table->string('nama_negara');

            $table->string('ibu_kota')->nullable();

            $table->string('wilayah');

            $table->string('mata_uang');

            $table->string('kode_mata_uang',10);

            $table->string('bahasa');

            $table->bigInteger('populasi')->nullable();

            $table->string('bendera')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negara');
    }
};