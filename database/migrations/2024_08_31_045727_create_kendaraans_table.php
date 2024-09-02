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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_polisi')->unique();
            $table->string('merk_kendaraan');
            $table->string('tipe');
            $table->unsignedBigInteger('jenis_kendaraan_id');
            $table->unsignedBigInteger('model_id');
            $table->foreign('jenis_kendaraan_id')->references('id')->on('jenis_kendaraans')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('models')->onDelete('cascade');
            $table->year('tahun');
            $table->string('warna');
            $table->string('nomor_rangka');
            $table->string('nomor_mesin');
            $table->string('bahan_bakar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
