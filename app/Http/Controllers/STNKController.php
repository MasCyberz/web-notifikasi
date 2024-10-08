<?php

namespace App\Http\Controllers;

use App\Models\STNK;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class STNKController extends Controller
{
    //

    public function index(Request $request)
    {
        // Validasi input, termasuk pencarian berdasarkan bulan
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'entries' => 'nullable|integer|min:5|max:100',
            'year' => 'nullable|integer|digits:4|min:1900|max:' .  (intval(date('Y')) + 5),
            'month' => 'nullable|integer|min:1|max:12', // Validasi input bulan
        ]);

        // Menerima input untuk pencarian, jumlah entries per halaman, tahun, dan bulan
        $search = $validated['search'] ?? null;
        $entries = $validated['entries'] ?? 10; // Default entries per halaman adalah 10
        $year = $validated['year'] ?? null; // Ambil input year tanpa default value
        $month = $validated['month'] ?? null; // Ambil input month tanpa default value

        // Query untuk mengambil semua data STNK
        $stnksQuery = STNK::with('RelasiSTNKtoKendaraan')
            ->whereHas('RelasiSTNKtoKendaraan', function ($query) use ($search) {
                if ($search) {
                    $query->where('merk_kendaraan', 'like', "%$search%")
                        ->orWhere('tipe', 'like', "%$search%")
                        ->orWhere('nomor_polisi', 'like', "%$search%");
                }
            });

        // Hanya tambahkan filter year jika year tidak kosong atau null
        if (!empty($year)) {
            $stnksQuery->whereYear('tanggal_perpanjangan', $year);
        }

        // Hanya tambahkan filter month jika month tidak kosong atau null
        if (!empty($month)) {
            $stnksQuery->whereMonth('tanggal_perpanjangan', $month);
        }

        // Ambil semua data STNK terlebih dahulu (tanpa pagination)
        $stnks = $stnksQuery->get();

        // Group data berdasarkan id_kendaraan
        $groupedData = $stnks->groupBy('id_kendaraan');

        // Data yang akan ditampilkan
        $finalData = [];

        foreach ($groupedData as $id_kendaraan => $items) {
            // Filter untuk mendapatkan jenis perpanjangan
            $data1Tahun = $items->where('jenis_perpanjangan', '1 Tahun')->sortByDesc('tanggal_perpanjangan')->first();
            $data5Tahun = $items->where('jenis_perpanjangan', '5 Tahun')->sortByDesc('tanggal_perpanjangan')->first();

            // Ambil data terbaru dari masing-masing jenis perpanjangan
            $finalData[] = [
                'id_kendaraan' => $id_kendaraan,
                'nomor_polisi' => $items->first()->RelasiSTNKtoKendaraan->nomor_polisi,
                'tipe' => $items->first()->RelasiSTNKtoKendaraan->tipe,
                'pajak_1_tahun' => $data1Tahun ? $data1Tahun->biaya : 0,
                'pajak_5_tahun' => $data5Tahun ? $data5Tahun->biaya : 0,
                'tanggal_perpanjangan_1_tahun' => $data1Tahun ? \Carbon\Carbon::parse($data1Tahun->tanggal_perpanjangan)->Format('d F Y')  : null,
                'tanggal_perpanjangan_5_tahun' => $data5Tahun ? \Carbon\Carbon::parse($data5Tahun->tanggal_perpanjangan)->Format('d F Y')  : null,
            ];
        }

        $finalData = collect($finalData)->sortBy('nomor_polisi')->values()->all();

        // Lakukan pagination pada finalData
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $itemCollection = collect($finalData);

        // Pagination collection berdasarkan entries yang dipilih user
        $paginatedData = new LengthAwarePaginator(
            $itemCollection->forPage($currentPage, $entries),
            $itemCollection->count(),
            $entries,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        // Mengirim data ke view
        return view('stnk.index', [
            'finalData' => $paginatedData, // Data yang sudah dipaginate
            'stnks' => $paginatedData, // Tetap gunakan variabel ini untuk pagination
            'search' => $search,
            'entries' => $entries,
            'year' => $year,
            'month' => $month // Mengirim bulan ke view agar bisa diisi ulang
        ]);
    }


    public function history(Request $request)
    {
        // Menerima input untuk pencarian, jumlah entries per halaman, dan tahun
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // Default entries per halaman adalah 10
        $year = $request->input('year'); // Ambil input year tanpa default value

        // Query untuk mengambil semua data STNK dengan filter search
        $stnks = STNK::with('RelasiSTNKtoKendaraan')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('RelasiSTNKtoKendaraan', function ($q) use ($search) {
                    $q->where('merk_kendaraan', 'like', "%$search%")
                        ->orWhere('tipe', 'like', "%$search%")
                        ->orWhere('nomor_polisi', 'like', "%$search%");
                });
            })
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('tanggal_perpanjangan', $year);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($entries)
            ->appends($request->all());

        // Mengirim data ke view
        return view('stnk.history', compact('stnks', 'search', 'entries', 'year'));
    }




    public function detail($id_kendaraan)
    {
        // Mencari data STNK berdasarkan id_kendaraan
        $stnk = STNK::with('RelasiSTNKtoKendaraan')->where('id_kendaraan', $id_kendaraan)->first();

        if (!$stnk) {
            return redirect()->back()->with('error', 'Data STNK tidak ditemukan');
        }

        // Mengambil perpanjangan 1 tahun dan 5 tahun terbaru
        $perpanjangan_satu_tahun = STNK::where('id_kendaraan', $id_kendaraan)
            ->where('jenis_perpanjangan', '1 tahun')
            ->orderBy('tanggal_perpanjangan', 'desc')
            ->first();

        $perpanjangan_lima_tahun = STNK::where('id_kendaraan', $id_kendaraan)
            ->where('jenis_perpanjangan', '5 tahun')
            ->orderBy('tanggal_perpanjangan', 'desc')
            ->first();

        return view('stnk.detail', compact('stnk', 'perpanjangan_satu_tahun', 'perpanjangan_lima_tahun'));
    }



    public function create()
    { {
            // Ambil semua kendaraan
            $kendaraan = Kendaraan::all();

            return view('stnk.add', ['kendaraan' => $kendaraan]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required',
            'biaya' => 'required',
            'tgl_perpanjangan' => 'required|date',
            'jenis_perpanjangan' => 'required'
        ]);

        // Cek apakah kombinasi nomor_polisi, jenis_perpanjangan, dan tgl_perpanjangan sudah ada
        $existingSTNK = STNK::where('id_kendaraan', $request->nomor_polisi)
            ->where('jenis_perpanjangan', $request->jenis_perpanjangan)
            ->where('tanggal_perpanjangan', $request->tgl_perpanjangan)
            ->first();

        if ($existingSTNK) {
            // Jika data sudah ada, return dengan pesan error
            return redirect()->back()->with(
                'error', 'Kombinasi jenis perpanjangan dan tanggal perpanjangan untuk kendaraan ini sudah ada.',
            )->withInput();
        }

        // Jika validasi lolos, simpan data STNK
        STNK::create([
            'id_kendaraan' => $request->nomor_polisi,
            'biaya' => $request->biaya,
            'tanggal_perpanjangan' => $request->tgl_perpanjangan,
            'jenis_perpanjangan' => $request->jenis_perpanjangan
        ]);

        return redirect()->route('stnk-index')->with('success', 'Data STNK berhasil ditambahkan');
    }


    public function editSTNK($id_kendaraan)
    {
        // Cari semua data STNK terkait kendaraan
        $stnk = STNK::where('id_kendaraan', $id_kendaraan)->get();

        // Cari data perpanjangan terbaru untuk 1 tahun dan 5 tahun
        $perpanjangan_satu_tahun = $stnk->where('jenis_perpanjangan', '1 Tahun')->sortByDesc('tanggal_perpanjangan')->first();
        $perpanjangan_lima_tahun = $stnk->where('jenis_perpanjangan', '5 Tahun')->sortByDesc('tanggal_perpanjangan')->first();

        // Dapatkan kendaraan terkait
        $kendaraanTerkait = Kendaraan::find($id_kendaraan);

        return view('stnk.edit', compact('kendaraanTerkait', 'perpanjangan_satu_tahun', 'perpanjangan_lima_tahun'));
    }

    public function updateSTNK(Request $request, $id_kendaraan)
    {
        // Validasi input
        $request->validate([
            'tgl_perpanjangan_1_tahun' => 'nullable|date',
            'biaya_perpanjangan_1_tahun' => 'nullable',
            'tgl_perpanjangan_5_tahun' => 'nullable|date',
            'biaya_perpanjangan_5_tahun' => 'nullable',
        ]);

        // Update perpanjangan 1 tahun
        if ($request->input('tgl_perpanjangan_1_tahun')) {
            // Ambil data terbaru untuk jenis perpanjangan 1 tahun
            $stnk1Tahun = STNK::where('id_kendaraan', $id_kendaraan)
                ->where('jenis_perpanjangan', '1 Tahun')
                ->orderBy('tanggal_perpanjangan', 'desc')
                ->first(); // Ambil satu record terbaru

            // Update hanya jika ada data terbaru
            if ($stnk1Tahun) {
                $stnk1Tahun->update([
                    'tanggal_perpanjangan' => $request->input('tgl_perpanjangan_1_tahun'),
                    'biaya' => $request->input('biaya_perpanjangan_1_tahun'),
                ]);
            }
        }

        // Update perpanjangan 5 tahun
        if ($request->input('tgl_perpanjangan_5_tahun')) {
            // Ambil data terbaru untuk jenis perpanjangan 5 tahun
            $stnk5Tahun = STNK::where('id_kendaraan', $id_kendaraan)
                ->where('jenis_perpanjangan', '5 Tahun')
                ->orderBy('tanggal_perpanjangan', 'desc')
                ->first(); // Ambil satu record terbaru

            // Update hanya jika ada data terbaru
            if ($stnk5Tahun) {
                $stnk5Tahun->update([
                    'tanggal_perpanjangan' => $request->input('tgl_perpanjangan_5_tahun'),
                    'biaya' => $request->input('biaya_perpanjangan_5_tahun'),
                ]);
            }
        }

        return redirect()->route('stnk-index');
    }




    public function deleteSTNK($id_kendaraan)
    {
        $stnks = STNK::with('RelasiSTNKtoKendaraan')->where('id_kendaraan', $id_kendaraan)->get();

        // Menghapus setiap STNK yang ditemukan
        foreach ($stnks as $stnk) {
            $stnk->delete();
        };

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('stnk-index')->with('success', 'Data STNK berhasil dihapus');
    }
}
