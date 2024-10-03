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
        Schema::table('kir_histories', function (Blueprint $table) {
            $table->enum('periode', ['periode 1', 'periode 2'])->after('alasan_tidak_lulus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kir_histories', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
};
