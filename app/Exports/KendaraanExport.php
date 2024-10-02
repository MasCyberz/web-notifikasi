<?php

namespace App\Exports;

use App\Models\Kendaraan;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
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
    }

    public function headings(): array
    {
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

    public function styles(Worksheet $sheet)
    {
        // Define styles for the headings
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'B7DEE8', // Change this to the desired fill color
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ];

        // Apply heading styles
        $sheet->getStyle('A1:M1')->applyFromArray($styleArray);

        // Apply border styles to data rows
        $rowCount = $sheet->getHighestRow(); // Get the highest row count for data
        $sheet->getStyle("A2:M{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Auto-size columns A to M
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
