<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\STNK;
use Illuminate\Http\Request;

class STNKController extends Controller
{
    //

    public function index(Request $request){
        // Menerima input untuk pencarian, jumlah entries per halaman, dan tahun
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // Default entries per halaman adalah 8
        $year = $request->input('year', \Carbon\Carbon::now()->format('Y')); // Default tahun adalah tahun saat ini

        // Query untuk mengambil data STNK dengan filter search dan year
        $stnks = STNK::with('RelasiSTNKtoKendaraan')
            ->whereHas('RelasiSTNKtoKendaraan', function ($query) use ($search) {
                if ($search) {
                    $query->where('merk_kendaraan', 'like', "%$search%")
                        ->orWhere('tipe', 'like', "%$search%")
                        ->orWhere('nomor_polisi', 'like', "%$search%");
                }
            })
            ->whereYear('tanggal_perpanjangan', $year)
            ->paginate($entries); // Pagination dengan jumlah entries yang dipilih

        // Mengirim data ke view
        return view('stnk.index', compact('stnks', 'search', 'entries', 'year'));
    }

    public function create()
    {
        // Ambil semua id kendaraan yang sudah ada di tabel STNK
        $kendaraanDenganSTNK = STNK::pluck('id_kendaraan')->toArray();

        // Ambil kendaraan yang belum memiliki STNK
        $kendaraanTanpaSTNK = Kendaraan::whereNotIn('id', $kendaraanDenganSTNK)->get();

        return view('stnk.add', ['kendaraanTanpaSTNK' => $kendaraanTanpaSTNK]);
    }

    public function store(request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required',
            'biaya' => 'required',
            'tgl_perpanjangan' => 'required',
        ]);

        // dd($request->all());

        $stnk = STNK::Create([
            'id_kendaraan' => $request->nomor_polisi,
            'biaya' => $request->biaya,
            'tanggal_perpanjangan' => $request->tgl_perpanjangan,
        ]);
        return redirect()->route('stnk-index')->with('success', 'Data STNK berhasil ditambahkan');
    }

    public function editSTNK($id)
    {
        $stnk = STNK::find($id);

        $kendaraanTerkait = Kendaraan::find($stnk->id_kendaraan);

        return view('stnk.edit', [
            'stnk' => $stnk,
            'kendaraanTerkait' => $kendaraanTerkait
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
