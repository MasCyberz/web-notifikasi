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
            $table->dropColumn(columns: 'status');
        });

        Schema::table('kir_histories', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'nonaktif', 'pending'])->default('aktif')->nullable()->after('tanggal_expired_kir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kir_histories', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('kir_histories', function (Blueprint $table) {
            $table->enum('status', ['lulus', 'tidak lulus'])->default('lulus')->nullable()->after('tanggal_expired_kir');
        });
    }
};
