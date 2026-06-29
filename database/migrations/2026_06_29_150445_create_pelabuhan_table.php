<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelabuhan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('negara_id')
                ->constrained('negara')
                ->cascadeOnDelete();

            $table->string('nama_pelabuhan');

            $table->string('kota');

            $table->decimal('lintang',10,7);

            $table->decimal('bujur',10,7);

            $table->enum('status_operasional',[
                'Normal',
                'Padat',
                'Tutup'
            ])->default('Normal');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelabuhan');
    }
};