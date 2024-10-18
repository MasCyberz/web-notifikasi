<?php

namespace App\Http\Controllers;

use App\Models\KIR;
use App\Models\KIRHistories;
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
        $totalStnk = STNK::whereYear('tanggal_perpanjangan', Carbon::now()->year)
        ->distinct('id_kendaraan') // Menghitung hanya unique id_kendaraan
        ->count('id_kendaraan');;

        $bulanIni = Carbon::now()->format('m');
        $totalStnkBulanIni = Stnk::whereYear('tanggal_perpanjangan', Carbon::now()->year)
            ->whereMonth('tanggal_perpanjangan', $bulanIni)
            ->distinct('id_kendaraan') // Menghitung hanya unique id_kendaraan
            ->count('id_kendaraan');

        // Total KIR berdasarkan tahun
        $totalKIR = KIRHistories::whereYear('tanggal_expired_kir', Carbon::now()->year)
        ->distinct('kirs_id') // Menghitung hanya unique id_kendaraan
        ->count('kirs_id');

        // Total KIR berdasarkan bulan dan tahun
        $totalKIRBulanIni = KIRHistories::whereYear('tanggal_expired_kir', Carbon::now()->year)
            ->whereMonth('tanggal_expired_kir', $bulanIni)
            ->distinct('kirs_id') // Menghitung hanya unique id_kendaraan
            ->count('kirs_id');

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
        $kirNotifications = KIRHistories::all()->map(function ($kirHistory) use ($today) {
            $deadline = Carbon::parse($kirHistory->tanggal_expired_kir);
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

            $kirHistory->message = $message;
            $kirHistory->kategori_waktu = $kategoriWaktu;
            $kirHistory->tipe_notifikasi = 'KIR';
            $kirHistory->warna = $warna;
            $kirHistory->judul = $judul;
            $kirHistory->tenggat = $deadline;

            return $kirHistory;
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

        // Ambil data KIR dan kir_histories
        $kirData = KIR::with(['kirHistories' => function ($query) {
            $query->orderBy('tanggal_expired_kir', 'asc'); // Urutkan berdasarkan tanggal expired
        }])->get();

        // Ambil data STNK
        $stnkData = STNK::all()->map(function ($stnk) {
            $stnk->tanggal_perpanjangan = Carbon::parse($stnk->tanggal_perpanjangan);
            return $stnk;
        });

        $notifikasi = collect();

        // Gabungkan data KIR
        foreach ($kirData as $kir) {
            foreach ($kir->kirHistories as $history) {
                // Cek apakah ada perpanjangan terbaru yang lebih jauh dari tanggal expired saat ini
                $latestHistory = $kir->kirHistories->sortByDesc('tanggal_expired_kir')->first();
                if ($history->tanggal_expired_kir < $latestHistory->tanggal_expired_kir) {
                    // Jika ada perpanjangan terbaru, skip notifikasi ini
                    continue;
                }

                // Hitung selisih hari dari tanggal expired KIR ke hari ini
                $daysToExpire = Carbon::parse($history->tanggal_expired_kir)->diffInDays($today, false);

                // Periksa apakah rentang berada antara H-45 sampai H-10 dan H-10 sampai hari H
                if ($daysToExpire >= -45 && $daysToExpire <= 0) {
                    $notifikasi->push((object) [
                        'id' => $history->id,
                        'warna' => ($daysToExpire >= -10 && $daysToExpire <= 0) ? 'warning' : 'primary',
                        'judul' => ($daysToExpire == 0) ? 'Hari ini KIR' : "H$daysToExpire KIR",
                        'message' => $daysToExpire == 0 ? 'Hari ini' : abs($daysToExpire) . ' hari.',
                        'tanggal_perpanjangan' => Carbon::parse($history->tanggal_expired_kir),
                        'relasiSTNKtoKendaraan' => $kir->kendaraan,
                        'tipe_notifikasi' => 'KIR',
                        'kirHistories' => $kir->kirHistories,
                    ]);
                }
            }
        }

        // Gabungkan data STNK
        foreach ($stnkData as $stnk) {
            // Cek apakah ada perpanjangan terbaru yang lebih jauh dari tanggal perpanjangan saat ini
            $latestStnk = STNK::where('id_kendaraan', $stnk->id_kendaraan)
                ->where('jenis_perpanjangan', $stnk->jenis_perpanjangan)
                ->orderBy('tanggal_perpanjangan', 'desc')
                ->first();

            if ($stnk->tanggal_perpanjangan < $latestStnk->tanggal_perpanjangan) {
                // Jika ada perpanjangan terbaru, skip notifikasi ini
                continue;
            }

            // Hitung selisih hari dari tanggal perpanjangan STNK ke hari ini
            $daysToExpire = $stnk->tanggal_perpanjangan->diffInDays($today, false);

            // Periksa apakah rentang berada antara H-45 sampai H-10 dan H-10 sampai hari H
            if ($daysToExpire >= -45 && $daysToExpire <= 0) {
                $notifikasi->push((object) [
                    'id' => $stnk->id,
                    'warna' => ($daysToExpire >= -10 && $daysToExpire <= 0) ? 'warning' : 'primary',
                    'judul' => ($daysToExpire == 0) ? 'Hari ini STNK' : "H$daysToExpire STNK",
                    'message' => $daysToExpire == 0 ? 'Hari ini' : abs($daysToExpire) . ' hari.',
                    'tanggal_perpanjangan' => $stnk->tanggal_perpanjangan,
                    'relasiSTNKtoKendaraan' => $stnk->relasiSTNKtoKendaraan,
                    'jenis_perpanjangan' => $stnk->jenis_perpanjangan,
                    'tipe_notifikasi' => 'STNK',
                ]);
            }
        }

        // Urutkan berdasarkan tanggal perpanjangan
        $notifikasi = $notifikasi->sortBy(function ($item) {
            return $item->tanggal_perpanjangan;
        });

        // Hanya tampilkan notifikasi yang jatuh tempo dalam 45 hari ke depan dan yang jatuh tempo hari ini
        $notifikasi = $notifikasi->filter(function ($item) use ($today) {
            $daysToExpire = $item->tanggal_perpanjangan->diffInDays($today);
            return ($daysToExpire <= 45 && $item->tanggal_perpanjangan->isFuture()) || $item->tanggal_perpanjangan->isToday();
        });

        return view('pemberitahuan-lainnya', compact('today', 'notifikasi'));
    }

    public function belumPerpanjang()
    {
        $today = Carbon::today();

        // Ambil data KIR dan kir_histories
        $kirData = KIR::with(['kirHistories' => function ($query) {
            $query->orderBy('tanggal_expired_kir', 'asc'); // Urutkan berdasarkan tanggal expired
        }])->get();

        // Ambil data STNK
        $stnkData = STNK::all()->map(function ($stnk) {
            $stnk->tanggal_perpanjangan = Carbon::parse($stnk->tanggal_perpanjangan);
            return $stnk;
        });

        $notifikasi = collect();

        // Gabungkan data KIR
        foreach ($kirData as $kir) {
            foreach ($kir->kirHistories as $history) {
                // Cek apakah tanggal expired sudah lewat dan belum diperpanjang
                $latestHistory = $kir->kirHistories->sortByDesc('tanggal_expired_kir')->first();
                if ($history->tanggal_expired_kir < $today && $history->tanggal_expired_kir == $latestHistory->tanggal_expired_kir) {
                    // Hitung selisih hari dari tanggal expired KIR ke hari ini
                    $daysOverdue = abs($today->diffInDays(Carbon::parse($history->tanggal_expired_kir), false));

                    // Tampilkan notifikasi jika KIR belum diperpanjang dan sudah melewati tenggat
                    $notifikasi->push((object) [
                        'id' => $history->id,
                        'warna' => 'danger', // Warna merah untuk notifikasi overdue
                        'judul' => 'KIR Melewati Tenggat',
                        'message' => "KIR sudah lewat {$daysOverdue} hari.",
                        'tanggal_expired_kir' => Carbon::parse($history->tanggal_expired_kir),
                        'relasiSTNKtoKendaraan' => $kir->kendaraan,
                        'tipe_notifikasi' => 'KIR',
                    ]);
                }
            }
        }

        // Gabungkan data STNK
        foreach ($stnkData as $stnk) {
            // Cek apakah tanggal perpanjangan sudah lewat dan belum diperpanjang
            $latestStnk = STNK::where('id_kendaraan', $stnk->id_kendaraan)
                ->where('jenis_perpanjangan', $stnk->jenis_perpanjangan)
                ->orderBy('tanggal_perpanjangan', 'desc')
                ->first();

            if ($stnk->tanggal_perpanjangan < $today && $stnk->tanggal_perpanjangan == $latestStnk->tanggal_perpanjangan) {
                // Hitung selisih hari dari tanggal perpanjangan STNK ke hari ini
                $daysOverdue = abs($today->diffInDays(Carbon::parse($stnk->tanggal_perpanjangan), false));

                // Tampilkan notifikasi jika STNK belum diperpanjang dan sudah melewati tenggat
                $notifikasi->push((object) [
                    'id' => $stnk->id,
                    'warna' => 'danger', // Warna merah untuk notifikasi overdue
                    'judul' => 'STNK Melewati Tenggat',
                    'message' => "STNK sudah lewat {$daysOverdue} hari.",
                    'tanggal_perpanjangan' => Carbon::parse($stnk->tanggal_perpanjangan),
                    'relasiSTNKtoKendaraan' => $stnk->relasiSTNKtoKendaraan,
                    'jenis_perpanjangan' => $stnk->jenis_perpanjangan,
                    'tipe_notifikasi' => 'STNK',
                ]);
            }
        }

        // Urutkan berdasarkan tanggal expired/perpanjangan yang paling lama
        $notifikasi = $notifikasi->sortBy(function ($item) {
            return $item->tanggal_expired_kir ?? $item->tanggal_perpanjangan;
        });

        // dd($notifikasi);

        return view('belum-perpanjang', compact('notifikasi'));
    }



    public function detailAlert($id, $tipe)
    {
        if ($tipe === 'STNK') {
            // Cari STNK dengan ID
            $stnk = STNK::where('id', $id)->first();
            if ($stnk && $stnk->biaya) {
                return view('detail-alert', [
                    'notifikasi' => $stnk,
                    'tipe' => $tipe,
                ]);
            }
        } elseif ($tipe === 'KIR') {
            // Cari KIR dengan ID
            $kir = KIRHistories::with('kir') // Ambil relasi dengan tabel KIR
                ->where('id', $id)
                ->first();

            if ($kir && $kir->kir->nomor_uji_kendaraan) {
                return view('detail-alert', [
                    'notifikasi' => $kir->kir,
                    'tipe' => $tipe,
                    'KIRHistory' => $kir, // Mengirimkan histori spesifik
                ]);
            }
        }

        // Jika tidak ditemukan di kedua tabel
        abort(404);
    }
}
