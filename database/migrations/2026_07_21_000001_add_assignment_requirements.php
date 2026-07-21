<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->string('port_type')->nullable()->after('longitude');
        });

        Schema::create('positive_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->timestamps();
        });

        Schema::create('negative_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->timestamps();
        });

        if (Schema::hasTable('news') && ! Schema::hasTable('news_cache')) {
            Schema::rename('news', 'news_cache');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('news_cache') && ! Schema::hasTable('news')) {
            Schema::rename('news_cache', 'news');
        }

        Schema::dropIfExists('negative_words');
        Schema::dropIfExists('positive_words');

        Schema::table('ports', function (Blueprint $table) {
            $table->dropColumn('port_type');
        });
    }
};
