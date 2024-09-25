<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class STNK extends Model
{
    use HasFactory;

    protected $table = 'stnks';

    protected $dates = ['tanggal_perpanjangan'];

    protected $fillable = [
        'id_kendaraan',
        'biaya',
        'tanggal_perpanjangan',
        'jenis_perpanjangan',
        'updated_at',
        'alasan'
    ];

    public function RelasiSTNKtoKendaraan(){
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id');
    }

}
