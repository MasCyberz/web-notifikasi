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
        Schema::create('kir_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kirs_id');
            $table->foreign('kirs_id')->references('id')->on('kirs')->onDelete('cascade');
            $table->date('tanggal_expired_kir');
            $table->enum('status', ['lulus', 'tidak lulus'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kir_histories');
    }
};
