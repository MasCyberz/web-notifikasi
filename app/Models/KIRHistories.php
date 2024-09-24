<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KIRHistories extends Model
{
    protected $table = 'kir_histories';

    protected $guarded = ['id'];

    protected $fillable = [
        'kirs_id',
        'tanggal_expired_kir',
        'status',
        'alasan_tidak_lulus',
        'created_at',
        'updated_at',
    ];

    public function kir(){
        return $this->belongsTo(KIR::class, 'kirs_id', 'id');
    }

    use HasFactory;
}
