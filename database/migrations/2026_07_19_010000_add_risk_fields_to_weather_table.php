<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('weather', function (Blueprint $table) {
            $table->decimal('rainfall', 8, 2)->nullable()->after('temperature');
            $table->decimal('storm_risk', 8, 2)->default(0)->after('weather_code');
            $table->timestamp('observed_at')->nullable()->after('storm_risk');
        });
    }

    public function down(): void
    {
        Schema::table('weather', fn (Blueprint $table) => $table->dropColumn(['rainfall', 'storm_risk', 'observed_at']));
    }
};
