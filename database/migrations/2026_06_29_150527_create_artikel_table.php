<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('judul');

            $table->longText('isi');

            $table->enum('status',[
                'Draft',
                'Publikasi'
            ])->default('Draft');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};