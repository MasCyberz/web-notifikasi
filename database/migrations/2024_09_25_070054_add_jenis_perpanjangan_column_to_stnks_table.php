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
        Schema::table('stnks', function (Blueprint $table) {
            $table->enum('jenis_perpanjangan', ['1 Tahun', '5 Tahun'])->after('tanggal_perpanjangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stnks', function (Blueprint $table) {
            $table->dropColumn('jenis_perpanjangan');
        });
    }
};
