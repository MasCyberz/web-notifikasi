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
            $table->string('nomor_bpkb')->after('bahan_bakar')->nullable();
            $table->year('tahun_registrasi')->after('nomor_bpkb')->nullable();
            $table->string('ident')->after('tahun_registrasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropColumn(['nomor_bpkb', 'tahun_registrasi', 'ident']);
        });
    }
};
