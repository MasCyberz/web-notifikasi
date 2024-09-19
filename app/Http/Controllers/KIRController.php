<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KIR;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KIRController extends Controller
{
    public function index(Request $request)
{
    // Get search, entries, and year from request
    $search = $request->input('search');
    $entries = $request->input('entries', 10); // Default 8 entries per page
    $year = $request->input('year', \Carbon\Carbon::now()->year); // Default to current year

    // Query the KIR data
    $kir = KIR::with('kendaraan')
        ->whereHas('kendaraan', function($query) use ($search) {
            // Filter by search if available
            if ($search) {
                $query->where('nomor_polisi', 'like', '%' . $search . '%')
                      ->orWhere('tipe', 'like', '%' . $search . '%');
            }
        })
        // Filter by year
        ->whereYear('tanggal_expired_kir', $year)
        // Paginate based on entries per page
        ->paginate($entries)->appends($request->query());

    return view('kir.index', compact('kir'));
}


    public function create()
    {
        $kendaraans = Kendaraan::where('nomor_polisi', 'LIKE', '% 7%')
            ->orWhere('nomor_polisi', 'LIKE', '% 9%')
            ->get();
        return view('kir.add', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kendaraan_id' => 'required | exists:kendaraans,id',
            'nomor_uji_kendaraan' => [
                'required',
                Rule::unique('kirs')->where(function ($query) use ($request) {
                    return $query->where('bulan_uji', date('m', strtotime($request->tanggal_expired_kir)))
                    ->where('tahun_uji', date('Y', strtotime($request->tanggal_expired_kir)));
                })
            ],
            'tanggal_expired_kir' => 'required',
        ]);

        $kir = KIR::create($validate);

        return redirect()->route('kir-index')->with('success', 'KIR berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kir = KIR::find($id);
        $kendaraans = Kendaraan::where('nomor_polisi', 'LIKE', '% 7%')
            ->orWhere('nomor_polisi', 'LIKE', '% 9%')
            ->get();
        return view('kir.edit', compact('kir', 'kendaraans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nomor_uji_kendaraan' => 'required',
            'tanggal_expired_kir' => 'required',
        ]);
        $kir = KIR::find($id);

        $kir->update($request->all());

        return redirect()->route('kir-index')->with('success', 'KIR berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kir = KIR::find($id);
        $kir->delete();
        return redirect()->route('kir-index')->with('success', 'KIR berhasil dihapus.');
    }
}
