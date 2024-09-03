<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraans';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'nomor_polisi',
        'merk_kendaraan',
        'tipe',
        'jenis_kendaraan',
        'model_kendaraan_id',
        'tahun',
        'warna',
        'nomor_rangka',
        'nomor_mesin',
        'bahan_bakar',
    ];

    public function modelKendaraan()
    {
        return $this->belongsTo(ModelKendaraan::class,  'id');
    }
}
