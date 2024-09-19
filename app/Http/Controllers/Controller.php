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

    public function dashboard()
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

        // STNK Notifications
        $stnkNotifications = STNK::all()->map(function ($stnk) use ($today) {
            $deadline = Carbon::parse($stnk->tanggal_perpanjangan);
            $diffInDays = $today->diffInDays($deadline, false);
            $diffInHours = $today->diffInHours($deadline, false);
            $diffInMinutes = $today->diffInMinutes($deadline, false);

            if ($diffInDays < 0) {
                return null;
            }

            $kategoriWaktu = '';
            $warna = '';

            // Tentukan judul berdasarkan rentang waktu
            if ($diffInDays >= 11 && $diffInDays <= 45) {
                $judul = 'Pembuatan PR STNK';
            } elseif ($diffInDays <= 10 && $diffInDays > 0) {
                $judul = 'Perpanjangan STNK';
            } elseif ($diffInDays === 0) {
                $judul = 'Perpanjangan STNK Hari Ini';
            } else {
                $judul = 'STNK Telah Jatuh Tempo';
            }

            if ($diffInDays > 45) {
                return null;
            } elseif ($diffInDays > 10) {
                $message = "$diffInDays hari lagi.";
                $kategoriWaktu = 'H-45';
                $warna = 'primary';
            } elseif ($diffInDays > 0) {
                $message = "$diffInDays hari lagi.";
                $kategoriWaktu = 'H-10';
                $warna = 'warning';
            } elseif ($diffInDays === 0 && $diffInHours > 0) {
                $message = "$diffInHours jam lagi.";
                $kategoriWaktu = 'Hari H';
                $warna = 'danger';
            } elseif ($diffInHours === 0 && $diffInMinutes > 0) {
                $message = "$diffInMinutes menit lagi.";
                $kategoriWaktu = 'Hari H';
                $warna = 'danger';
            } else {
                $message = "Telah jatuh tempo.";
                $kategoriWaktu = 'Hari H';
                $warna = 'danger';
            }

            $stnk->message = $message;
            $stnk->kategori_waktu = $kategoriWaktu;
            $stnk->tipe_notifikasi = 'STNK';
            $stnk->warna = $warna;
            $stnk->judul = $judul;
            $stnk->tenggat = $deadline;

            return $stnk;
        })->filter();

        // KIR Notifications
        $kirNotifications = KIR::all()->map(function ($kir) use ($today) {
            $deadline = Carbon::parse($kir->tanggal_expired_kir);
            $diffInDays = $today->diffInDays($deadline, false);
            $diffInHours = $today->diffInHours($deadline, false);
            $diffInMinutes = $today->diffInMinutes($deadline, false);

            if ($diffInDays < 0) {
                return null;
            }

            $kategoriWaktu = '';
            $warna = '';

            // Tentukan judul berdasarkan rentang waktu
            if ($diffInDays >= 11 && $diffInDays <= 45) {
                $judul = 'Pembuatan PR KIR';
            } elseif ($diffInDays <= 10 && $diffInDays > 0) {
                $judul = 'Perpanjangan KIR';
            } elseif ($diffInDays === 0) {
                $judul = 'Perpanjangan KIR Hari Ini';
            } else {
                $judul = 'KIR Telah Jatuh Tempo';
            }

            if ($diffInDays > 45) {
                return null;
            } elseif ($diffInDays > 10) {
                $message = "$diffInDays hari lagi.";
                $kategoriWaktu = 'H-45';
                $warna = 'primary';
            } elseif ($diffInDays > 0) {
                $message = "$diffInDays hari lagi.";
                $kategoriWaktu = 'H-10';
                $warna = 'warning';
            } elseif ($diffInDays === 0 && $diffInHours > 0) {
                $message = "$diffInHours jam lagi.";
                $kategoriWaktu = 'Hari H';
                $warna = 'danger';
            } elseif ($diffInHours === 0 && $diffInMinutes > 0) {
                $message = "$diffInMinutes menit lagi.";
                $kategoriWaktu = 'Hari H';
                $warna = 'danger';
            } else {
                $message = "Telah jatuh tempo.";
                $kategoriWaktu = 'Hari H';
                $warna = 'danger';
            }

            $kir->message = $message;
            $kir->kategori_waktu = $kategoriWaktu;
            $kir->tipe_notifikasi = 'KIR';
            $kir->warna = $warna;
            $kir->judul = $judul;
            $kir->tenggat = $deadline;

            return $kir;
        })->filter();

        // Gabungkan notifikasi STNK dan KIR
        $allNotifications = $stnkNotifications->concat($kirNotifications)->sortBy(function ($item) {
            return $item->tenggat;
        });
        $allNotifications = $allNotifications->take(4);

        return view('home', [
            'dataUser' => $dataUser,
            'totalStnk' => $totalStnk,
            'totalStnkBulanIni' => $totalStnkBulanIni,
            'totalKIR' => $totalKIR,
            'totalKIRBulanIni' => $totalKIRBulanIni,
            'allNotifications' => $allNotifications,
            // 'stnkPR' => $stnkPR,
            // 'kirPR' => $kirPR,
            // 'stnkTenDays' => $stnkTenDays,
            // 'kirTenDays' => $kirTenDays,
            // 'stnkToday' => $stnkToday,
            // 'kirToday' => $kirToday,
        ]);
    }

    public function pemberitahuanlainnya()
    {
        $today = Carbon::today();

        // Ambil data dari model dan parse tanggal
        // $kirData = KIR::all()->map(function ($kir) {
        //     $kir->tanggal_expired_kir = Carbon::parse($kir->tanggal_expired_kir);
        //     return $kir;
        // });

        // Ambil data dan parse tanggal untuk STNK
        // $stnkData = STNK::all()->map(function ($stnk) {
        //     $stnk->tanggal_perpanjangan = Carbon::parse($stnk->tanggal_perpanjangan);
        //     return $stnk;
        // });

        // // Filter data KIR untuk H-10, 45 hari, dan Hari H
        // $hMinus10KIR = $kirData->filter(function ($kir) use ($today) {
        //     $daysToExpire = $kir->tanggal_expired_kir->diffInDays($today);
        //     return $daysToExpire > 0 && $daysToExpire <= 10;
        // });

        // $prDateKIR = $kirData->filter(function ($kir) use ($today) {
        //     $daysToExpire = $kir->tanggal_expired_kir->diffInDays($today);
        //     return $daysToExpire > 10 && $daysToExpire <= 45; // Menampilkan dari 45 hari hingga H-10
        // });

        // $hariIniKIR = $kirData->filter(function ($kir) use ($today) {
        //     return $kir->tanggal_expired_kir->isSameDay($today);
        // });

        // Filter data KIR untuk H-10, 45 hari, dan Hari H
        // $hMinus10STNK = $stnkData->filter(function ($stnk) use ($today) {
        //     $daysToExpire = $stnk->tanggal_perpanjangan->diffInDays($today);
        //     return $daysToExpire > 0 && $daysToExpire <= 10; // Menampilkan dari H-10 hingga hari H
        // });

        // $prDateSTNK = $stnkData->filter(function ($stnk) use ($today) {
        //     $daysToExpire = $stnk->tanggal_perpanjangan->diffInDays($today);
        //     return $daysToExpire >= 10 && $daysToExpire <= 45; // Menampilkan dari 45 hari hingga H-10
        // });

        // $hariIniSTNK = $stnkData->filter(function ($stnk) use ($today) {
        //     return $stnk->tanggal_perpanjangan->isSameDay($today);
        // });

        // Ambil data dari model dan parse tanggal
        $kirData = KIR::all()->map(function ($kir) use ($today) {
            $kir->tanggal_expired_kir = Carbon::parse($kir->tanggal_expired_kir);
            return $kir;
        });

        $stnkData = STNK::all()->map(function ($stnk) use ($today) {
            $stnk->tanggal_perpanjangan = Carbon::parse($stnk->tanggal_perpanjangan);
            return $stnk;
        });

        $notifikasi = collect();

// Gabungkan data KIR
        foreach ($kirData as $kir) {
            $daysToExpire = $kir->tanggal_expired_kir->diffInDays($today);
            $notifikasi->push((object) [
                'id' => $kir->id,
                'warna' => ($daysToExpire > 0 && $daysToExpire <= 10) ? 'warning' : ($daysToExpire > 10 && $daysToExpire <= 45 ? 'primary' : 'danger'),
                'judul' => ($daysToExpire > 0 && $daysToExpire <= 10) ? 'H-10 KIR' : ($daysToExpire > 10 && $daysToExpire <= 45 ? '1,5 bulan KIR' : 'KIR hari ini'),
                'message' => $daysToExpire <= 0 ? 'Hari ini' : "akan jatuh tempo dalam $daysToExpire hari.",
                'tanggal_perpanjangan' => $kir->tanggal_expired_kir,
                'relasiSTNKtoKendaraan' => $kir->kendaraan,
            ]);
        }

// Gabungkan data STNK
        foreach ($stnkData as $stnk) {
            $daysToExpire = $stnk->tanggal_perpanjangan->diffInDays($today);
            $notifikasi->push((object) [
                'id' => $stnk->id,
                'warna' => ($daysToExpire > 0 && $daysToExpire <= 10) ? 'warning' : ($daysToExpire > 10 && $daysToExpire <= 45 ? 'primary' : 'danger'),
                'judul' => ($daysToExpire > 0 && $daysToExpire <= 10) ? 'H-10 STNK' : ($daysToExpire > 10 && $daysToExpire <= 45 ? '1,5 bulan STNK' : 'STNK hari ini'),
                'message' => $daysToExpire <= 0 ? 'Hari ini' : "akan jatuh tempo dalam $daysToExpire hari.",
                'tanggal_perpanjangan' => $stnk->tanggal_perpanjangan,
                'relasiSTNKtoKendaraan' => $stnk->relasiSTNKtoKendaraan,
            ]);
        }

// Urutkan berdasarkan tanggal perpanjangan
        $notifikasi = $notifikasi->sortBy(function ($item) {
            return $item->tanggal_perpanjangan;
        });

// Hanya tampilkan notifikasi yang jatuh tempo dalam 45 hari ke depan dan yang jatuh tempo hari ini
        $notifikasi = $notifikasi->filter(function ($item) use ($today) {
            $daysToExpire = $item->tanggal_perpanjangan->diffInDays($today);
            return $daysToExpire <= 45 && $item->tanggal_perpanjangan->isFuture() || $item->tanggal_perpanjangan->isToday();
        });

        return view('pemberitahuan-lainnya', compact(
            // 'hariIniKIR',
            // 'hMinus10KIR',
            // 'prDateKIR',
            // 'hariIniSTNK',
            // 'hMinus10STNK',
            // 'prDateSTNK',
            'today',
            'notifikasi'
        ));
    }

    public function detailAlert($id)
    {
        // Cari STNK dengan ID
        $stnk = STNK::where('id', $id)->first();

        // Jika STNK ditemukan dan memiliki kolom biaya
        if ($stnk && $stnk->biaya) {
            return view('detail-alert', ['notifikasi' => $stnk, 'tipe' => 'STNK']);
        }

        // Cari KIR dengan ID
        $kir = KIR::where('id', $id)->first();

        // Jika KIR ditemukan dan memiliki kolom nomor_uji_kendaraan
        if ($kir && $kir->nomor_uji_kendaraan) {
            return view('detail-alert', ['notifikasi' => $kir, 'tipe' => 'KIR']);
        }

        // Jika tidak ditemukan di kedua tabel
        abort(404);

        // Tentukan tipe notifikasi
        $tipe = $notifikasi instanceof STNK ? 'STNK' : 'KIR';

        dd($notifikasi, $tipe);

        return view('detail-alert', compact('notifikasi', 'tipe'));
    }
}
