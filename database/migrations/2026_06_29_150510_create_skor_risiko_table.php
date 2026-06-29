<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skor_risiko', function (Blueprint $table) {

            $table->id();

            $table->foreignId('negara_id')
                ->constrained('negara')
                ->cascadeOnDelete();

            $table->decimal('skor_cuaca',5,2)->default(0);

            $table->decimal('skor_ekonomi',5,2)->default(0);

            $table->decimal('skor_kurs',5,2)->default(0);

            $table->decimal('skor_berita',5,2)->default(0);

            $table->decimal('skor_pelabuhan',5,2)->default(0);

            $table->decimal('skor_total',5,2)->default(0);

            $table->enum('kategori',[
                'Sangat Aman',
                'Risiko Rendah',
                'Risiko Sedang',
                'Risiko Tinggi',
                'Kritis'
            ]);
            $table->date('tanggal_perhitungan');
            $table->string('status_resiko');
            $table->text('rekomendasi')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_risiko');
    }
};