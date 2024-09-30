<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KIR;
use App\Models\KIRHistories;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KIRController extends Controller
{
    public function index(Request $request)
    {
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
            'januari' => '01', 'jan' => '01',
            'februari' => '02', 'feb' => '02',
            'maret' => '03', 'mar' => '03',
            'april' => '04', 'apr' => '04',
            'mei' => '05', 'mei' => '05',
            'juni' => '06', 'jun' => '06',
            'juli' => '07', 'jul' => '07',
            'agustus' => '08', 'agus' => '08', 'agu' => '08',
            'september' => '09', 'sep' => '09', 'sept' => '09',
            'oktober' => '10', 'okt' => '10',
            'november' => '11', 'nov' => '11',
            'desember' => '12', 'des' => '12',
        ];

        // Periksa jika input pencarian adalah tanggal
        if ($search) {
            // Coba memisahkan angka dari bulan jika ditulis dengan spasi
            preg_match('/(\d{1,2})(?:\s+)?([a-zA-Z]+)/', strtolower($search), $matches);

            // Periksa jika ditulis tanpa spasi
            if (empty($matches)) {
                preg_match('/(\d{1,2})([a-zA-Z]+)/', strtolower($search), $matches);
            }

            if (count($matches) == 3) {
                $day = $matches[1]; // Ambil angka hari
                $month = $matches[2]; // Ambil nama bulan

                if (array_key_exists($month, $monthMap)) {
                    $monthNumber = $monthMap[$month];
                    // Buat format tanggal
                    $dateToSearch = Carbon::createFromFormat('Y-m-d', now()->year . '-' . $monthNumber . '-' . $day)->format('Y-m-d');
                    // Filter berdasarkan tanggal spesifik
                    $query->whereDate('tanggal_expired_kir', $dateToSearch);
                }
            } else {
                // Jika tidak ada format tanggal, cari berdasarkan nomor_polisi atau tipe
                $query->whereHas('kir.kendaraan', function ($q) use ($search) {
                    $q->where('nomor_polisi', 'like', "%$search%")
                        ->orWhere('tipe', 'like', "%$search%");
                });
            }
        }

        // Custom sorting: expired dates at the bottom
        $query->orderByRaw('CASE WHEN tanggal_expired_kir >= NOW() THEN 0 ELSE 1 END')
            ->orderBy('tanggal_expired_kir', 'asc'); // Then sort by the date itself

        // Paginate the results
        $kir = $query->with('kir.kendaraan')
            ->paginate($entries)
            ->appends($request->all());

        return view('kir.index', compact('kir'));
    }

    public function detail($id)
    {
        $kir = KIRHistories::with('kir.kendaraan')->find($id);
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
            'tanggal_expired_kir' => 'required|date',
        ]);

        // Pengecekan manual untuk nomor_uji_kendaraan pada bulan yang sama
        $existingKir = KIR::where('nomor_uji_kendaraan', $request->nomor_uji_kendaraan)
            ->whereHas('kirHistories', function ($query) use ($request) {
                $query->whereMonth('tanggal_expired_kir', date('m', strtotime($request->tanggal_expired_kir)))
                    ->whereYear('tanggal_expired_kir', date('Y', strtotime($request->tanggal_expired_kir)));
            })->exists();

        // Jika nomor uji kendaraan sudah ada pada bulan dan tahun yang sama, beri pesan error
        if ($existingKir) {
            return back()->withErrors(['nomor_uji_kendaraan' => 'Nomor uji kendaraan sudah ada untuk bulan dan tahun ini.']);
        }

        // Simpan data ke tabel KIR
        $kir = KIR::create([
            'kendaraan_id' => $request->kendaraan_id,
            'nomor_uji_kendaraan' => $request->nomor_uji_kendaraan,
        ]);

        // Simpan tanggal_expired_kir ke tabel kir_histories
        $kir->kirHistories()->create([
            'tanggal_expired_kir' => $request->tanggal_expired_kir,
        ]);

        return redirect()->route('kir-index')->with('success', 'KIR berhasil ditambahkan.');
    }

    public function edit($id)
    {
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
            $history->status = 'lulus'; // Simpan status lulus ke history

            // Tambahkan 6 bulan dari tanggal_expired_kir
            $nextSixMonthsDate = $expiredDate->copy()->addMonthsNoOverflow(6);

            // Buat histori baru dengan tanggal_expired_kir yang diperbarui
            KIRHistories::create([
                'kirs_id' => $history->kirs_id, // ID KIR dari histori yang dipilih
                'tanggal_expired_kir' => $nextSixMonthsDate, // Tanggal expired 6 bulan ke depan
                'status' => 'lulus', // Set status lulus untuk histori baru
            ]);

        } elseif ($request->action === 'tidak lulus') {
            $history->status = 'tidak lulus'; // Simpan status tidak lulus ke history

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
