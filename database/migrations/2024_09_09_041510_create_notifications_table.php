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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['STNK', 'KIR']); // Tipe notifikasi: 'STNK' atau 'KIR'
            $table->text('pesan'); // Isi pesan notifikasi
            $table->json('data_kendaraan'); // Data kendaraan terkait dalam format JSON
            $table->boolean('diproses')->default(false); // Status apakah notifikasi sudah diproses oleh siapapun
            $table->unsignedTinyInteger('bulan'); // Bulan notifikasi
            $table->unsignedSmallInteger('tahun'); // Tahun notifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
