<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_tukar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('negara_id')
                  ->constrained('negara')
                  ->cascadeOnDelete();
            $table->string('mata_uang');
            $table->decimal('nilai_tukar', 15, 4);
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_tukar');
    }
};
