<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string' => 'Kolom :attribute harus berupa string.',
    'max' => [
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
    ],
    'integer' => 'Kolom :attribute harus berupa angka.',
    'exists' => 'Kolom :attribute tidak valid.',
    'digits' => 'Kolom :attribute harus memiliki :digits digit.',
    'nullable' => 'Kolom :attribute boleh kosong.',
    'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
    'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
    'unique' => 'Kolom :attribute sudah digunakan.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, dan tanda hubung.',
    'regex' => 'Kolom :attribute memiliki format yang tidak valid.',

    // Tambahkan aturan lain yang diperlukan di sini

    'custom' => [
        'nomor_polisi' => [
            'required' => 'Nomor polisi harus diisi.',
        ],
        'merk_kendaraan' => [
            'required' => 'Merk kendaraan harus diisi.',
        ],
        'tipe' => [
            'nullable' => 'Tipe kendaraan boleh kosong.',
        ],
        'jenis_kendaraan' => [
            'nullable' => 'Jenis kendaraan boleh kosong.',
        ],
        'model_kendaraan_id' => [
            'required' => 'Model kendaraan harus dipilih.',
            'exists' => 'Model kendaraan yang dipilih tidak valid.',
        ],
        'tahun' => [
            'required' => 'Tahun kendaraan harus diisi.',
            'integer' => 'Tahun kendaraan harus berupa angka.',
            'digits' => 'Tahun kendaraan harus terdiri dari 4 digit.',
        ],
        'warna' => [
            'nullable' => 'Warna kendaraan boleh kosong.',
        ],
        'nomor_rangka' => [
            'nullable' => 'Nomor rangka boleh kosong.',
        ],
        'nomor_mesin' => [
            'nullable' => 'Nomor mesin boleh kosong.',
        ],
        'bahan_bakar' => [
            'nullable' => 'Bahan bakar boleh kosong.',
        ],
        'nomor_bpkb' => [
            'nullable' => 'Nomor BPKB boleh kosong.',
        ],
        'nomor_stnk' => [
            'nullable' => 'Nomor STNK boleh kosong.',
        ],
    ],

    'attributes' => [
        'nomor_polisi' => 'Nomor Polisi',
        'merk_kendaraan' => 'Merk Kendaraan',
        'tipe' => 'Tipe Kendaraan',
        'jenis_kendaraan' => 'Jenis Kendaraan',
        'model_kendaraan_id' => 'Model Kendaraan',
        'tahun' => 'Tahun Kendaraan',
        'warna' => 'Warna Kendaraan',
        'nomor_rangka' => 'Nomor Rangka',
        'nomor_mesin' => 'Nomor Mesin',
        'bahan_bakar' => 'Bahan Bakar',
        'nomor_bpkb' => 'Nomor BPKB',
        'nomor_stnk' => 'Nomor STNK',
    ],
];
