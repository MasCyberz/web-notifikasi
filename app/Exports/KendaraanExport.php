<?php

namespace App\Exports;

use App\Models\Kendaraan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KendaraanExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];

        // Ambil daftar jenis kendaraan yang unik
        $jenisKendaraanList = Kendaraan::select('jenis_kendaraan')->distinct()->pluck('jenis_kendaraan');

        // Buat sheet untuk setiap jenis kendaraan
        foreach ($jenisKendaraanList as $jenisKendaraan) {
            $sheets[] = new KendaraanPerJenisExport($jenisKendaraan);
        }

        return $sheets;
    }
}
