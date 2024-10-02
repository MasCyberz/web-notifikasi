<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom username hanya jika belum ada dan tempatkan setelah 'name'
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('name');
            }
        });

        // Pindahkan data dari kolom email ke kolom username
        DB::table('users')->update([
            'username' => DB::raw('email')
        ]);

        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom username menjadi tidak nullable
            $table->string('username')->nullable(false)->change();

            // Pastikan indeks unik hanya ditambahkan jika belum ada
            $table->unique('username', 'users_username_unique');

            // Hapus kolom email
            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan kolom email
            $table->string('email')->unique()->nullable()->after('name');
        });

        // Pindahkan data kembali dari kolom username ke kolom email
        DB::table('users')->update([
            'email' => DB::raw('username')
        ]);

        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom username
            $table->dropColumn('username');
        });
    }
};
