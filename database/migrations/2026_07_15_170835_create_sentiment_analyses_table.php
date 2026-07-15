<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentiment_analyses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('news_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('positive')->default(0);

            $table->integer('negative')->default(0);

            $table->integer('neutral')->default(0);

            $table->string('result');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiment_analyses');
    }
};