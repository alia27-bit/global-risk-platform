<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('countries', fn (Blueprint $table) => $table->string('alpha2', 2)->nullable()->index()->after('code'));
    }

    public function down(): void
    {
        Schema::table('countries', fn (Blueprint $table) => $table->dropColumn('alpha2'));
    }
};
