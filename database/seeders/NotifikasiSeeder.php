<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notifikasi::create([
            'user_id' => 1, // Contoh user_id
            'type' => 'STNK',
            'pesan' => 'Perpanjangan STNK diperlukan dalam 2 bulan.',
            'dibaca' => false,
        ]);
    }
}
