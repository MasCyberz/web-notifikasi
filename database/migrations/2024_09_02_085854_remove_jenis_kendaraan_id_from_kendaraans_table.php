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
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropForeign(['jenis_kendaraan_id']);
            $table->dropColumn('jenis_kendaraan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->foreignId('jenis_kendaraan_id')->nullable()->constrained('jenis_kendaraans
            ')->onDelete('cascade');
        });
    }
};
