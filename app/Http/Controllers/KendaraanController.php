<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\ModelKendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil input dari search dan entries
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // Default entries adalah 8 jika tidak ada input

        // Query dasar untuk mengambil data kendaraan
        $query = Kendaraan::query();

        // Jika ada input search, lakukan pencarian berdasarkan beberapa kolom
        if ($search) {
            $query->where('nomor_polisi', 'like', "%{$search}%")
                ->orWhere('merk_kendaraan', 'like', "%{$search}%")
                ->orWhere('tipe', 'like', "%{$search}%")
                ->orWhere('jenis_kendaraan', 'like', "%{$search}%")
                ->orWhere('user_kendaraan', 'like', "%{$search}%")
            //   ->orWhere('modelKendaraan.name', 'like', "%{$search}%")
            ;
        }

        $query->orderBy('created_at', 'desc');

        // Mengambil data kendaraan dengan pagination berdasarkan input entries
        $kendaraans = $query->paginate($entries)
            ->appends($request->all());

        // Melempar data ke view
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function detail($id)
    {
        $kendaraan = Kendaraan::with('modelKendaraan')->find($id);
        return view('kendaraan.detail', compact('kendaraan'));
    }

    public function create()
    {
        $models = ModelKendaraan::all();
        return view('kendaraan.add', compact('models'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_polisi' => 'required|string|max:255',
            'merk_kendaraan' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'jenis_kendaraan' => 'required|string|max:255',
            'model_kendaraan_id' => 'required|exists:model_kendaraans,id',
            'tahun' => 'required|integer|digits:4',
            'user_kendaraan' => 'required|string|max:100',
            'warna' => 'required|string|max:255',
            'nomor_rangka' => 'required|string|max:255',
            'nomor_mesin' => 'required|string|max:255',
            'bahan_bakar' => 'required|string|max:255',
            'nomor_bpkb' => 'required|string',
            'tahun_registrasi' => 'required|string|max:255',
            'ident' => 'required|string|max:255',
        ]);

        // dd($request->all());

        Kendaraan::create([
            'nomor_polisi' => $request->nomor_polisi,
            'merk_kendaraan' => $request->merk_kendaraan,
            'tipe' => $request->tipe,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'model_kendaraan_id' => $request->model_kendaraan_id,
            'tahun' => $request->tahun,
            'user_kendaraan' => $request->user_kendaraan,
            'warna' => $request->warna,
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin' => $request->nomor_mesin,
            'bahan_bakar' => $request->bahan_bakar,
            'nomor_bpkb' => $request->nomor_bpkb,
            'tahun_registrasi' => $request->tahun_registrasi,
            'ident' => $request->ident,
        ]);

        return redirect()->route('kendaraan-index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function storeNewModels(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:model_kendaraans,name',
        ]);

        $model = ModelKendaraan::create([
            'name' => $request->name,
        ]);

        return response()->json($model);
    }

    public function deleteModels(Request $request)
    {
        $modelId = $request->input('id');

        $model = ModelKendaraan::find($modelId);

        if ($model) {
            $model->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $models = ModelKendaraan::all(); // Mengambil semua model kendaraan dari database

        return view('kendaraan.edit', compact('kendaraan', 'models'));
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->update($request->all());

        return redirect()->route('kendaraan-index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('kendaraan-index')->with('success', 'Kendaraan berhasil dihapus');
    }
}
