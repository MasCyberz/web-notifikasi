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

        // Jika merk kendaraan berubah, tambahkan 2 baris kosong dan judul merk baru di kolom A
        if ($this->currentMerk !== $kendaraan->merk_kendaraan) {
            $this->currentMerk = $kendaraan->merk_kendaraan;

            // Reset nomor urut untuk setiap grup baru
            $this->rowCounter = 1;

            // Tambahkan baris kosong untuk spacing
            $data[] = []; // Baris kosong pertama
            $data[] = ['Merk: ' . $this->currentMerk]; // Baris kedua dengan judul merk kendaraan di kolom A
        }

        // Data kendaraan normal dimulai dari kolom B (termasuk nomor urut di kolom B)
        $data[] = [
            $this->rowCounter, // Nomor urut dimulai dari 1 untuk setiap grup baru
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
        // Tambahkan heading untuk kolom nomor urut
        return [
            'No.', // Heading untuk nomor urut
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
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Times New Roman',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'B7DEE8',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ];

        $sheet->getStyle('A1:N1')->applyFromArray($styleArray);

        foreach (range('B', 'N') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $rowCount = $sheet->getHighestRow();

        // Pengaturan untuk kolom A (Nomor Urut) dilakukan lebih awal
        $sheet->getStyle('A2:A' . $rowCount)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
        ]);

        // Menerapkan pengaturan untuk header merk
        foreach ($sheet->getRowIterator() as $row) {
            $cellValue = $sheet->getCell('A' . $row->getRowIndex())->getValue();

            if (strpos($cellValue, 'Merk: ') !== false) {
                // Merge sel untuk header Merk dari A sampai N
                $sheet->mergeCells('A' . $row->getRowIndex() . ':N' . $row->getRowIndex());

                // Atur alignment dan style untuk header merk setelah merge
                $sheet->getStyle('A' . $row->getRowIndex())->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT, // Rata kiri untuk header merk
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension($row->getRowIndex())->setRowHeight(25);
            }
        }

        // Memberikan border tipis untuk data kendaraan
        for ($row = 2; $row <= $rowCount; $row++) {
            for ($col = 'B'; $col <= 'N'; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();
                if ($cellValue !== null && $cellValue !== '') {
                    $sheet->getStyle($col . $row)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => Color::COLOR_BLACK],
                            ],
                        ],
                    ]);
                }
            }
        }

        // Menerapkan pengaturan font dan alignment untuk data kendaraan selain kolom A
        $sheet->getStyle("B2:N{$rowCount}")->applyFromArray([
            'font' => [
                'name' => 'Times New Roman',
                'size' => 12,
            ],
        ]);

        // Membekukan baris kedua (supaya judul kolom tetap terlihat)
        $sheet->freezePane('A2');
    }
}
