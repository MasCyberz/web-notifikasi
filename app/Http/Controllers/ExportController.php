<?php

namespace App\Http\Controllers;

use App\Exports\KendaraanExport;
use App\Exports\STNKKIRExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function ExportKendaraanKirStnk(Request $request)
    {
        $exportType = $request->input('exportType');
        $hariIni = Carbon::now()->format('d-m-Y');

        if ($exportType == 'vehicle') {
            $filename = 'kendaraan_' . $hariIni . '.xlsx';
            return Excel::download(new KendaraanExport(), $filename);
        }

        if ($exportType == 'kir_stnk') {
            $year = $request->input('year');
            $month = $request->input('month');
            $platNomor = $request->input('platNomor', []);

            $filename = 'kir_stnk_' . $year . '_' . $month . '_' . $hariIni . '.xlsx';

            return Excel::download(new STNKKIRExport($year, $month, $platNomor), $filename);
        }
    }
}
