<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KIRHistories extends Model
{
    protected $table = 'kir_histories';

    protected $guarded = ['id'];

    protected $fillable = [
        'kirs_id',
        'tanggal_expired_kir',
        'status',
        'alasan_tidak_lulus',
        'periode',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_expired_kir' => 'datetime', // Cast to DateTime
    ];

    public function kir()
    {
        return $this->belongsTo(KIR::class, 'kirs_id', 'id');
    }

    use HasFactory;
    public static function updateStatus()
    {
        $kirHistories = KIRHistories::where('status', '!=', 'pending')->get();

        foreach ($kirHistories as $kirHistory) {
            if (Carbon::parse($kirHistory->tanggal_expired_kir)->isPast()) {
                $kirHistory->status = 'nonaktif';
                $kirHistory->save();
            }
        }
    }
}
