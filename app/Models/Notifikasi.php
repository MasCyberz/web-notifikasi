<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = ['type', 'pesan', 'data_kendaraan', 'diproses', 'bulan', 'tahun'];

    protected $casts = [
        'data_kendaraan' => 'array', // Mengonversi kolom JSON menjadi array
        'diproses' => 'boolean',
    ];


}
