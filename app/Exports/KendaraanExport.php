<?php

namespace App\Exports;

use App\Models\Kendaraan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KendaraanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Kendaraan::orderByRaw('CAST(REGEXP_SUBSTR(nomor_polisi, "[0-9]+") AS UNSIGNED)')->get();
    }

    public function map($kendaraan): array
    {
        return [
            $kendaraan->user_kendaraan,
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
        ];
    }

    public function headings(): array
    {
        return [
            'User Kendaraan',
            'Plat Nomor',
            'Nomor BPKB',
            'Merk Kendaraan',
            'Tipe',
            'Jenis Kendaraan',
            'Model',
            'Tahun',
            'Tahun Registrasi',
            'Nomor Rangka',
            'Nomor Mesin',
            'Warna',
            'Bahan Bakar',
        ];
    }

    public function styles(Worksheet $sheet)
    {

        // Mendapatkan tahun dinamis saat ini
        $tahunSekarang = date('Y');

        // Menambahkan teks di atas heading dengan merge & center
        $sheet->mergeCells('A1:M1'); // Merge cells dari kolom A sampai M di baris pertama
        $sheet->setCellValue('A1', 'DATA KENDARAAN ' . $tahunSekarang); // Menambahkan teks dengan tahun dinamis

        // Styling untuk judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18, // Ukuran font lebih besar
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Center horizontal
                'vertical' => Alignment::VERTICAL_CENTER, // Center vertical
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM, // Border tebal untuk heading
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, // Filling tebal
                'startColor' => [
                    'argb' => 'C4D79B', // Warna tebal
                ],
            ],
        ]);

        // Styling untuk heading (baris pertama)
        $sheet->getStyle('A2:M2')->applyFromArray([
            'font' => [
                'bold' => true, // Membuat teks tebal
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM, // Border tebal untuk heading
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, // Filling tebal
                'startColor' => [
                    'argb' => 'B7DEE8', // Warna tebal
                ],
            ],
        ]);

        // Styling untuk seluruh konten (baris di bawah heading)
        $sheet->getStyle('A3:M' . ($sheet->getHighestRow()))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN, // Border tipis untuk konten
                    ],
                ],
            ]);
    }
}
