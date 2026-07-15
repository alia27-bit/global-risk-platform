<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_comparisons', function (Blueprint $table) {

            $table->id();

            $table->foreignId('country_a')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->foreignId('country_b')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_comparisons');
    }
};