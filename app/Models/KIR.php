<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KIR extends Model
{
    use HasFactory;

    protected $table = 'kirs';

    protected $dates = ['tanggal_expired_kir'];

    protected $guarded = ['id'];

    protected $fillable = [
        'kendaraan_id',
        'nomor_uji_kendaraan',
        'tanggal_expired_kir',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id', 'id');
    }

    public function kirHistories()
    {
        return $this->hasMany(KIRHistories::class, 'kirs_id', 'id');
    }

}
