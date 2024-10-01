<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KIR;
use App\Models\KIRHistories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KIRController extends Controller
{
    public function index(Request $request)
    {
        KIRHistories::updateStatus();
        // Retrieve the filter parameters
        $entries = $request->input('entries', 10); // Default to 10 entries per page
        $year = $request->input('year');
        $search = $request->input('search');

        // Build the query with filters
        $query = KIRHistories::query();

        if ($year) {
            $query->whereYear('tanggal_expired_kir', $year);
        }

        $monthMap = [
            'januari' => '01',
            'jan' => '01',
            'februari' => '02',
            'feb' => '02',
            'maret' => '03',
            'mar' => '03',
            'april' => '04',
            'apr' => '04',
            'mei' => '05',
            'juni' => '06',
            'jun' => '06',
            'juli' => '07',
            'jul' => '07',
            'agustus' => '08',
            'agus' => '08',
            'agu' => '08',
            'september' => '09',
            'sep' => '09',
            'sept' => '09',
            'oktober' => '10',
            'okt' => '10',
            'november' => '11',
            'nov' => '11',
            'desember' => '12',
            'des' => '12',
        ];

        // Pencarian berdasarkan tanggal atau nomor polisi
        if ($search) {
            preg_match('/(\d{1,2})(?:\s+)?([a-zA-Z]+)/', strtolower($search), $matches);
            if (empty($matches)) {
                preg_match('/(\d{1,2})([a-zA-Z]+)/', strtolower($search), $matches);
            }

            if (count($matches) == 3) {
                $day = $matches[1];
                $month = $matches[2];

                if (array_key_exists($month, $monthMap)) {
                    $monthNumber = $monthMap[$month];
                    $dateToSearch = Carbon::createFromFormat('Y-m-d', now()->year . '-' . $monthNumber . '-' . $day)->format('Y-m-d');
                    $query->whereDate('tanggal_expired_kir', $dateToSearch);
                }
            } else {
                $query->whereHas('kir.kendaraan', function ($q) use ($search) {
                    $q->where('nomor_polisi', 'like', "%$search%")
                        ->orWhere('tipe', 'like', "%$search%");
                });
            }
        }

        // Subquery to get the latest tanggal_expired_kir per kendaraan
        $latestKIRs = KIRHistories::select('kirs_id', DB::raw('MAX(tanggal_expired_kir) as max_tanggal'))
            ->groupBy('kirs_id');

        // Join subquery to get the latest records
        $query->joinSub($latestKIRs, 'latest_kirs', function ($join) {
            $join->on('kir_histories.kirs_id', '=', 'latest_kirs.kirs_id')
                ->whereColumn('kir_histories.tanggal_expired_kir', 'latest_kirs.max_tanggal');
        });

        // Sorting: expired dates at the bottom, then by date
        $query->orderByRaw('CASE WHEN tanggal_expired_kir >= NOW() THEN 0 ELSE 1 END')
            ->orderBy('tanggal_expired_kir', 'asc');

        // Paginate the results
        $kir = $query->with('kir.kendaraan')
            ->paginate($entries)
            ->appends($request->all());

        return view('kir.index', compact('kir'));
    }



    public function detail($id)
    {
        // Cari history KIR berdasarkan ID yang diterima
        $kirHistory = KIRHistories::findOrFail($id);

        // Subquery untuk mendapatkan data kir_histories terbaru untuk kirs_id yang sama
        $latestKIRs = KIRHistories::select('kirs_id', DB::raw('MAX(tanggal_expired_kir) as max_tanggal'))
            ->where('kirs_id', $kirHistory->kirs_id) // Sesuai dengan kirs_id dari kir yang dipilih
            ->groupBy('kirs_id')
            ->first();

        // Query data yang sesuai dengan tanggal terbaru dan kirs_id yang didapat dari subquery
        $kir = KIRHistories::with('kir.kendaraan')
            ->where('kirs_id', $kirHistory->kirs_id)
            ->where('tanggal_expired_kir', $latestKIRs->max_tanggal)
            ->firstOrFail(); // Ambil data terbaru yang sesuai

        return view('kir.detail', compact('kir'));
    }

    public function create()
    {
        $kendaraans = Kendaraan::where('nomor_polisi', 'LIKE', '% 7%')
            ->orWhere('nomor_polisi', 'LIKE', '% 9%')
            ->orWhere('nomor_polisi', 'LIKE', '% 8%')
            ->get();
        return view('kir.add', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        // Validasi input awal
        $validate = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nomor_uji_kendaraan' => 'required|unique:kirs,nomor_uji_kendaraan',
        ]);

        // Simpan data ke tabel KIR
        $kir = KIR::create([
            'kendaraan_id' => $request->kendaraan_id,
            'nomor_uji_kendaraan' => $request->nomor_uji_kendaraan,
        ]);

        return redirect()->route('kir-index')->with('success', 'KIR berhasil ditambahkan.');
    }

    public function createPerpanjanganKIR()
    {
        // Ambil data KIR beserta kendaraan
        $KIRkendaraan = KIR::with('kendaraan')->get();

        return view('kir.addPerpanjangan', compact('KIRkendaraan'));
    }

    public function storePerpanjanganKIR(Request $request)
    {
        // Validasi input awal
        $validate = $request->validate([
            'kirs_id' => 'required',
            'tanggal_expired_kir' => 'required',
        ]);

        // Pengecekan manual untuk nomor_uji_kendaraan pada bulan yang sama
        $existingKir = KIR::where('id', $request->kirs_id)
            ->whereHas('kirHistories', function ($query) use ($request) {
                $query->whereMonth('tanggal_expired_kir', date('m', strtotime($request->tanggal_expired_kir)))
                    ->whereYear('tanggal_expired_kir', date('Y', strtotime($request->tanggal_expired_kir)));
            })->exists();

        // Jika nomor uji kendaraan sudah ada pada bulan dan tahun yang sama, beri pesan error
        if ($existingKir) {
            return back()->withErrors(['tanggal_expired_kir' => 'Nomor uji kendaraan sudah ada untuk bulan ini.']);
        }

        // Simpan data ke tabel KIR
        $kir = KIRHistories::create([
            'kirs_id' => $request->kirs_id,
            'tanggal_expired_kir' => $request->tanggal_expired_kir,
        ]);

        return redirect()->route('kir-index')->with('success', 'KIR berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Ambil data KIR beserta kendaraan
        $kir = KIRHistories::with('kir.kendaraan')->findorfail($id);
        $kendaraans = Kendaraan::where('nomor_polisi', 'LIKE', '% 7%')
            ->orWhere('nomor_polisi', 'LIKE', '% 9%')
            ->get();
        return view('kir.edit', compact('kir', 'kendaraans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_uji_kendaraan' => 'required',
            'tanggal_expired_kir' => 'required',
        ]);

        // Cari kirHistory berdasarkan ID
        $kirHistory = KIRHistories::findOrFail($id);

        // Cari kir terkait untuk mendapatkan kendaraan_id
        $kir = $kirHistory->kir;

        // Perbarui data kirHistory
        $kirHistory->update($request->only(['nomor_uji_kendaraan', 'tanggal_expired_kir']));

        // Periksa apakah kendaraan_id valid
        if (!$kir || !$kir->kendaraan_id) {
            return redirect()->back()->withErrors(['kendaraan_id' => 'Kendaraan tidak ditemukan']);
        }

        return redirect()->route('kir-index')->with('success', 'KIR berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kir = KIR::find($id);
        $kir->delete();
        return redirect()->route('kir-index')->with('success', 'KIR berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'alasan_tidak_lulus' => 'required|string',
        ]);

        // Cari data KIR berdasarkan ID
        $kirHistory = KIRHistories::findOrFail($id);

        // Pastikan status saat ini adalah nonaktif sebelum diubah
        if ($kirHistory->status === 'nonaktif') {
            // Ubah status menjadi pending dan tambahkan keterangan
            $kirHistory->status = 'pending';
            $kirHistory->alasan_tidak_lulus = $request->alasan_tidak_lulus;
            $kirHistory->save();
        }

        // dd($kirHistory);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('kir-index')->with('success', 'Status berhasil diubah menjadi pending.');
    }

    public function updateStatusKIR(Request $request, $id)
    {
        // Cari histori KIR berdasarkan ID yang dipilih
        $history = KIRHistories::findOrFail($id); // Mengambil histori KIR berdasarkan ID spesifik

        // Validasi input
        $request->validate([
            'action' => 'required|string',
            'alasan_tidak_lulus' => 'nullable|string|max:255', // Validasi untuk alasan tidak lulus
        ]);

        // Ambil tanggal_expired_kir dari histori KIR yang dipilih
        $expiredDate = \Carbon\Carbon::parse($history->tanggal_expired_kir);

        // Cek aksi yang diterima dari form
        if ($request->action === 'lulus') {
            $history->status = 'aktif'; // Simpan status lulus ke history

            // Tambahkan 6 bulan dari tanggal_expired_kir
            $nextSixMonthsDate = $expiredDate->copy()->addMonthsNoOverflow(6);

            // Buat histori baru dengan tanggal_expired_kir yang diperbarui
            KIRHistories::create([
                'kirs_id' => $history->kirs_id, // ID KIR dari histori yang dipilih
                'tanggal_expired_kir' => $nextSixMonthsDate, // Tanggal expired 6 bulan ke depan
            ]);
        } elseif ($request->action === 'tidak lulus') {
            $history->status = 'nonaktif'; // Simpan status tidak lulus ke history

            // Simpan alasan tidak lulus jika ada
            if ($request->filled('alasan_tidak_lulus')) {
                $history->alasan_tidak_lulus = $request->alasan_tidak_lulus;
            }

            // Tambahkan 1 bulan dari tanggal_expired_kir
            $nextMonthDate = $expiredDate->copy()->addMonthNoOverflow();

            // Buat histori baru dengan tanggal_expired_kir yang diperbarui
            KIRHistories::create([
                'kirs_id' => $history->kirs_id, // ID KIR dari histori yang dipilih
                'tanggal_expired_kir' => $nextMonthDate, // Tanggal expired 1 bulan ke depan
            ]);
        }

        // Simpan perubahan pada histori KIR yang dipilih
        $history->save();

        // dd($request->all());

        return redirect()->route('dashboard')->with('success', 'Status KIR berhasil diperbarui.');
    }
}
