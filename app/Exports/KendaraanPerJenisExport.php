<?php

namespace App\Exports;

use App\Models\Kendaraan;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KendaraanPerJenisExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $jenisKendaraan;
    protected $currentMerk = null; // Untuk melacak perubahan merk kendaraan
    protected $rowCounter = 1; // Untuk melacak nomor baris yang sedang diisi

    public function __construct($jenisKendaraan)
    {
        $this->jenisKendaraan = $jenisKendaraan;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Kendaraan::where('jenis_kendaraan', $this->jenisKendaraan)
            ->orderBy('merk_kendaraan')
            ->orderByRaw('CAST(REGEXP_SUBSTR(nomor_polisi, "[0-9]+") AS UNSIGNED)')
            ->get();
    }

    public function map($kendaraan): array
    {
        $data = [];

        // Jika merk kendaraan berubah, tambahkan 2 baris kosong dan judul merk baru
        if ($this->currentMerk !== $kendaraan->merk_kendaraan) {
            $this->currentMerk = $kendaraan->merk_kendaraan;

            // Tambahkan baris kosong untuk spacing
            $this->rowCounter++;

            // Tambahkan judul merk kendaraan (merge 2 row dan format besar serta bold)
            $data[] = ['Merk: ' . $this->currentMerk]; // Baris pertama dengan judul merk kendaraan
            $this->rowCounter++; // Increment row counter
        }

        // Data kendaraan normal setelah judul merk
        $data[] = [
            $kendaraan->nomor_polisi,
            $kendaraan->nomor_bpkb,
            $kendaraan->merk_kendaraan,
            $kendaraan->tipe,
            $kendaraan->jenis_kendaraan,
            optional($kendaraan->modelKendaraan)->name ?? null,
            $kendaraan->tahun,
            $kendaraan->tahun_registrasi,
            $kendaraan->nomor_rangka,
            $kendaraan->nomor_mesin,
            $kendaraan->warna,
            $kendaraan->bahan_bakar,
            $kendaraan->user_kendaraan,
        ];

        $this->rowCounter++; // Increment row counter

        return $data;
    }

    public function headings(): array
    {
        // Headings untuk kolom data
        return [
            'Plat Nomor',
            'Nomor BPKB',
            'Merk Kendaraan',
            'Tipe',
            'Jenis Kendaraan',
            'Model',
            'Tahun Pembuatan',
            'Tahun Registrasi',
            'Nomor Rangka',
            'Nomor Mesin',
            'Warna',
            'Bahan Bakar',
            'User Kendaraan',
        ];
    }

    public function title(): string
    {
        return $this->jenisKendaraan;
    }

    public function styles(Worksheet $sheet)
    {
        // Gaya untuk heading kolom data
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'B7DEE8', // Ubah warna fill sesuai kebutuhan
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ];

        // Terapkan style untuk heading
        $sheet->getStyle('A1:M1')->applyFromArray($styleArray);

        // Terapkan border untuk data
        $rowCount = $sheet->getHighestRow();
        $sheet->getStyle("A2:M{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Auto-size untuk kolom A sampai M
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Gaya untuk setiap judul merk kendaraan
        foreach ($sheet->getRowIterator() as $row) {
            $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();

            // Jika cell berisi teks "Merk: ", maka terapkan gaya
            if (strpos($cellValue, 'Merk: ') !== false) {
                $sheet->mergeCells('A' . $row->getRowIndex() . ':M' . $row->getRowIndex()); // Merge row
                $sheet->getStyle('A' . $row->getRowIndex())->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Ukuran lebih besar
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT, // Center alignment
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // Tambah tinggi baris untuk judul merk
                $sheet->getRowDimension($row->getRowIndex())->setRowHeight(25);
            }
        }
    }
}
