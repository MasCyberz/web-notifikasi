<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelKendaraan extends Model
{
    use HasFactory;

    protected $table = 'model_kendaraans';

    protected $fillable = [
        'name',
    ];

    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'model_kendaraan_id');
    }
}
