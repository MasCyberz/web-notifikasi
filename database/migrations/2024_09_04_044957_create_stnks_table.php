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
        Schema::create('stnks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kendaraan')->constrained('kendaraans')->onDelete('cascade');
            $table->string('biaya');
            $table->date('tanggal_perpanjangan');
            $table->string('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stnks');
    }
};
