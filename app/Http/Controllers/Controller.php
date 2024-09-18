<?php

namespace App\Http\Controllers;

use App\Models\KIR;
use App\Models\STNK;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        $dataUser = User::count();

        // Total STNK
        $totalStnk = STNK::whereYear('tanggal_perpanjangan', Carbon::now()->year)->count();

        $bulanIni = Carbon::now()->format('m');
        $totalStnkBulanIni = Stnk::whereYear('tanggal_perpanjangan', Carbon::now()->year)
            ->whereMonth('tanggal_perpanjangan', $bulanIni)
            ->count();

        // Total KIR
        $totalKIR = KIR::whereYear('tanggal_expired_kir', Carbon::now()->year)->count();

        $totalKIRBulanIni = KIR::whereYear('tanggal_expired_kir', Carbon::now()->year)
            ->whereMonth('tanggal_expired_kir', $bulanIni)
            ->count();

        // Untuk Menghitung H-45, H-10, Hari H
        $today = Carbon::today();
        $oneAndHalfMonth = $today->copy()->addMonths(1)->addDays(15);
        $tenDays = $today->copy()->addDays(10);

        // Query

        $stnkPR = STNK::whereBetween('tanggal_perpanjangan', [$today->copy()->addDays(11), $oneAndHalfMonth])
            ->whereDate('tanggal_perpanjangan', '!=', $today)
            ->with('relasiSTNKtoKendaraan') // Mengambil relasi kendaraan
            ->get();

        $kirPR = KIR::whereBetween('tanggal_expired_kir', [$today->copy()->addDays(11), $oneAndHalfMonth])
            ->whereDate('tanggal_expired_kir', '!=', $today)
            ->with('kendaraan') // Mengambil relasi kendaraan
            ->get();

        // Query untuk mendapatkan data PR STNK dalam 10 hari, kecuali hari ini
        $stnkTenDays = STNK::whereBetween('tanggal_perpanjangan', [$today, $tenDays])
            ->whereDate('tanggal_perpanjangan', '!=', $today)
            ->with('RelasiSTNKtoKendaraan') // Mengambil relasi kendaraan
            ->get();

// Query untuk mendapatkan data PR KIR dalam 10 hari, kecuali hari ini
        $kirTenDays = KIR::whereBetween('tanggal_expired_kir', [$today, $tenDays])
            ->whereDate('tanggal_expired_kir', '!=', $today)
            ->with('kendaraan') // Mengambil relasi kendaraan
            ->get();

        // Query untuk mendapatkan data PR STNK yang jatuh tempo hari ini
        $stnkToday = STNK::whereDate('tanggal_perpanjangan', $today)
            ->with('relasiSTNKtoKendaraan') // Mengambil relasi kendaraan
            ->get();

// Query untuk mendapatkan data PR KIR yang jatuh tempo hari ini
        $kirToday = KIR::whereDate('tanggal_expired_kir', $today)
            ->with('kendaraan') // Mengambil relasi kendaraan
            ->get();

        return view('home', [
            'dataUser' => $dataUser,
            'totalStnk' => $totalStnk,
            'totalStnkBulanIni' => $totalStnkBulanIni,
            'totalKIR' => $totalKIR,
            'totalKIRBulanIni' => $totalKIRBulanIni,
            'stnkPR' => $stnkPR,
            'kirPR' => $kirPR,
            'stnkTenDays' => $stnkTenDays,
            'kirTenDays' => $kirTenDays,
            'stnkToday' => $stnkToday,
            'kirToday' => $kirToday,
        ]);
    }

    public function pemberitahuanlainnya()
    {
        $today = Carbon::today();

        // Ambil data dari model dan parse tanggal
        $kirData = KIR::all()->map(function ($kir) {
            $kir->tanggal_expired_kir = Carbon::parse($kir->tanggal_expired_kir);
            return $kir;
        });

        // Ambil data dan parse tanggal untuk STNK
        $stnkData = STNK::all()->map(function ($stnk) {
            $stnk->tanggal_perpanjangan = Carbon::parse($stnk->tanggal_perpanjangan);
            return $stnk;
        });

        // Filter data KIR untuk H-10, 45 hari, dan Hari H
        $hMinus10KIR = $kirData->filter(function ($kir) use ($today) {
            $daysToExpire = $kir->tanggal_expired_kir->diffInDays($today);
            return $daysToExpire > 0 && $daysToExpire <= 10;
        });

        $prDateKIR = $kirData->filter(function ($kir) use ($today) {
            $daysToExpire = $kir->tanggal_expired_kir->diffInDays($today);
            return $daysToExpire > 10 && $daysToExpire <= 45; // Menampilkan dari 45 hari hingga H-10
        });

        $hariIniKIR = $kirData->filter(function ($kir) use ($today) {
            return $kir->tanggal_expired_kir->isSameDay($today);
        });

        // Filter data KIR untuk H-10, 45 hari, dan Hari H
        $hMinus10STNK = $stnkData->filter(function ($stnk) use ($today) {
            $daysToExpire = $stnk->tanggal_perpanjangan->diffInDays($today);
            return $daysToExpire > 0 && $daysToExpire <= 10; // Menampilkan dari H-10 hingga hari H
        });

        $prDateSTNK = $stnkData->filter(function ($stnk) use ($today) {
            $daysToExpire = $stnk->tanggal_perpanjangan->diffInDays($today);
            return $daysToExpire >= 10 && $daysToExpire <= 45; // Menampilkan dari 45 hari hingga H-10
        });

        $hariIniSTNK = $stnkData->filter(function ($stnk) use ($today) {
            return $stnk->tanggal_perpanjangan->isSameDay($today);
        });

        return view('pemberitahuan-lainnya', compact('hariIniKIR', 'hMinus10KIR', 'prDateKIR', 'hariIniSTNK', 'hMinus10STNK', 'prDateSTNK', 'today'));
    }
}
