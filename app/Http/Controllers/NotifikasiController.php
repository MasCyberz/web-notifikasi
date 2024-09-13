<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KIR;
use Carbon\Carbon;
use App\Models\Notifikasi;
use App\Models\STNK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotifikasiController extends Controller
{

    public function generateNotifications()
    {
        $nextMonth = Carbon::now()->addMonth();
        $month = $nextMonth->month;
        $year = $nextMonth->year;

        $stnkVehicles = STNK::whereMonth('tanggal_perpanjangan', $month)
            ->whereYear('tanggal_perpanjangan', $year)
            ->with('RelasiSTNKtoKendaraan')
            ->get();

        $kirVehicles = KIR::whereMonth('tanggal_expired_kir', $month)
            ->whereYear('tanggal_expired_kir', $year)
            ->with('kendaraan')
            ->get();


        $existingSTNKNotification = Notifikasi::where('type', 'STNK')
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->first();

        if ($stnkVehicles->isNotEmpty() && !$existingSTNKNotification) {
            Notifikasi::create([
                'type' => 'STNK',
                'pesan' => 'Perpanjangan STNK diperlukan.',
                'data_kendaraan' => $stnkVehicles->map(fn($vehicle) => [
                    'nomor_polisi' => $vehicle->RelasiSTNKtoKendaraan->nomor_polisi,
                    'tanggal_perpanjangan' => $vehicle->tanggal_perpanjangan
                ]),
                'bulan' => $month,
                'tahun' => $year,
            ]);
        }

        // Cek apakah ada notifikasi KIR untuk bulan dan tahun ini
        $existingKIRNotification = Notifikasi::where('type', 'KIR')
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->first();

        if ($kirVehicles->isNotEmpty() && !$existingKIRNotification) {
            Notifikasi::create([
                'type' => 'KIR',
                'pesan' => 'Perpanjangan KIR diperlukan.',
                'data_kendaraan' => $kirVehicles->map(fn($vehicle) => [
                    'nomor_polisi' => $vehicle->kendaraan->nomor_polisi,
                    'tanggal_expired_kir' => $vehicle->tanggal_expired_kir
                ]),
                'bulan' => $month,
                'tahun' => $year,
            ]);
        }

        return redirect()->route('notifikasi.index');
    }
    public function index()
    {
        // Mendapatkan notifikasi yang belum dibaca untuk user yang sedang login
        $notifications = Notifikasi::all();

        return view('notifikasi.index', compact('notifications'));
    }

    public function updateStatus($id)
    {
        $notification = Notifikasi::findOrFail($id);
        $notification->diproses = true;
        $notification->save();
        return redirect()->back()->with('success', 'Status notifikasi telah diupdate.');
    }
}
