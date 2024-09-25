<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\STNK;
use Illuminate\Http\Request;

class STNKController extends Controller
{
    //

    public function index(Request $request)
    {
        // Menerima input untuk pencarian, jumlah entries per halaman, dan tahun
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // Default entries per halaman adalah 10
        $year = $request->input('year'); // Ambil input year tanpa default value

        // Query untuk mengambil data STNK dengan filter search
        $stnks = STNK::with('RelasiSTNKtoKendaraan')
            ->whereHas('RelasiSTNKtoKendaraan', function ($query) use ($search) {
                if ($search) {
                    $query->where('merk_kendaraan', 'like', "%$search%")
                        ->orWhere('tipe', 'like', "%$search%")
                        ->orWhere('nomor_polisi', 'like', "%$search%");
                }
            });

        // Hanya tambahkan filter year jika year tidak kosong atau null
        if (!empty($year)) {
            $stnks = $stnks->whereYear('tanggal_perpanjangan', $year);
        }

        $stnks = $stnks->orderBy('created_at', 'desc');

        // Pagination dengan jumlah entries yang dipilih
        $stnks = $stnks->paginate($entries)->appends($request->all());

        // Mengirim data ke view
        return view('stnk.index', compact('stnks', 'search', 'entries', 'year'));
    }

    public function detail($id)
    {
        // Mengambil data STNK yang berelasi dengan kendaraan
        $stnk = STNK::with('RelasiSTNKtoKendaraan')->find($id);

        // Mengambil perpanjangan 1 tahun dan 5 tahun terbaru
        $perpanjangan_satu_tahun = STNK::where('id_kendaraan', $stnk->id_kendaraan)
            ->where('jenis_perpanjangan', '1 tahun')
            ->orderBy('tanggal_perpanjangan', 'desc')
            ->first();

        $perpanjangan_lima_tahun = STNK::where('id_kendaraan', $stnk->id_kendaraan)
            ->where('jenis_perpanjangan', '5 tahun')
            ->orderBy('tanggal_perpanjangan', 'desc')
            ->first();

        return view('stnk.detail', compact('stnk', 'perpanjangan_satu_tahun', 'perpanjangan_lima_tahun'));
    }


    public function create()
    {
        // public function create()
        {
            // Ambil semua kendaraan
            $kendaraan = Kendaraan::all();

            return view('stnk.add', ['kendaraan' => $kendaraan]);
        }
    }

    public function store(request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required',
            'biaya' => 'required',
            'tgl_perpanjangan' => 'required',
            'jenis_perpanjangan' => 'required'
        ]);

        // dd($request->all());

        $stnk = STNK::Create([
            'id_kendaraan' => $request->nomor_polisi,
            'biaya' => $request->biaya,
            'tanggal_perpanjangan' => $request->tgl_perpanjangan,
            'jenis_perpanjangan' => $request->jenis_perpanjangan
        ]);
        return redirect()->route('stnk-index')->with('success', 'Data STNK berhasil ditambahkan');
    }

    public function editSTNK($id)
    {
        $stnk = STNK::find($id);

        $kendaraanTerkait = Kendaraan::find($stnk->id_kendaraan);

        return view('stnk.edit', [
            'stnk' => $stnk,
            'kendaraanTerkait' => $kendaraanTerkait,
            // 'kendaraanTanpaSTNK' => $kendaraanTanpaSTNK
        ]);
    }

    public function deleteSTNK($id)
    {
        $stnk = STNK::find($id);
        $stnk->delete();
        return redirect()->route('stnk-index')->with('success', 'Data STNK berhasil dihapus');
    }
}
