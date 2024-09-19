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
        // Retrieve the filter parameters
        $entries = $request->input('entries', 10); // Default to 10 entries per page
        $year = $request->input('year');
        $search = $request->input('search');

        // Build the query with filters
        $query = KIR::query();

        if ($year) {
            $query->whereYear('tanggal_expired_kir', $year);
        }

        if ($search) {
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('nomor_polisi', 'like', "%$search%")
                    ->orWhere('tipe', 'like', "%$search%");
            });
        }

        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $kir = $query->with('kendaraan')
            ->paginate($entries)
            ->appends($request->all());
        return view('kir.index', compact('kir'));
    }

    public function detail($id)
    {
        $kir = KIR::with('kendaraan')->find($id);
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
        $validate = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'nomor_uji_kendaraan' => [
                'required',
                Rule::unique('kirs')->where(function ($query) use ($request) {
                    return $query->whereMonth('tanggal_expired_kir', date('m', strtotime($request->tanggal_expired_kir)))
                        ->whereYear('tanggal_expired_kir', date('Y', strtotime($request->tanggal_expired_kir)));
                }),
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
