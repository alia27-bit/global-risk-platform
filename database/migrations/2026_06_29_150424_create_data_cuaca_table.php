<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_cuaca', function (Blueprint $table) {

            $table->id();

            $table->foreignId('negara_id')
                  ->constrained('negara')
                  ->cascadeOnDelete();

            $table->decimal('suhu',5,2);

            $table->decimal('curah_hujan',5,2);

            $table->decimal('kecepatan_angin',5,2);

            $table->decimal('tingkat_badai',5,2)->default(0);

            $table->timestamp('waktu_pengambilan');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_cuaca');
    }
};