<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\ModelKendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('modelKendaraan')->get();
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        $models = ModelKendaraan::all();
        return view('kendaraan.add', compact('models'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'nomor_polisi' => 'required|string|max:255',
        //     'merk_kendaraan' => 'required|string|max:255'
        // ]);

        // dd($request->all());

        Kendaraan::create([
            'nomor_polisi' => $request->nomor_polisi,
            'merk_kendaraan' => $request->merk_kendaraan,
            'tipe' => $request->tipe,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'model_kendaraan_id' => $request->model_kendaraan_id,
            'tahun' => $request->tahun,
            'warna' => $request->warna,
            'nomor_rangka' => $request->nomor_rangka,
            'nomor_mesin' => $request->nomor_mesin,
            'bahan_bakar' => $request->bahan_bakar,
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
}
