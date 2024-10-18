<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\STNK;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class STNKExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $year;
    protected $month;
    protected $platNomor;

    public function __construct($year = null, $month = null, $platNomor = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->platNomor = $platNomor;
    }
    public function collection()
    {
        $exportData = collect(); // Collection to store all data for export

        // Retrieve STNK data with kendaraan relationship
        $stnks = STNK::with('RelasiSTNKtoKendaraan')
            ->when($this->year, function ($query) {
                return $query->whereYear('tanggal_perpanjangan', $this->year);
            })
            ->when($this->month, function ($query) {
                return $query->whereMonth('tanggal_perpanjangan', $this->month);
            })
            ->when($this->platNomor, function ($query) {
                return $query->whereHas('RelasiSTNKtoKendaraan', function ($subQuery) {
                    $subQuery->whereIn('nomor_polisi', (array) $this->platNomor);
                });
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_perpanjangan->format('n Y'); // Group by month number and year
            })
            ->sortBy(function ($group, $key) {
                [$month, $year] = explode(' ', $key); // Extract month and year
                return sprintf('%04d-%02d', $year, $month); // Create sortable string by year-month
            });

        // Loop through each month group and build the data
        foreach ($stnks as $monthYear => $groupedData) {
            // Get the name of the month (January, February, etc.)
            $monthName = Carbon::createFromFormat('n Y', $monthYear)->format('F Y');

            // Reset nomor urut for each month
            $nomorUrut = 1;

            // Add a header for the month (empty values for merging cells)
            $exportData->push([
                'No.' => '', // Kolom nomor untuk header
                'Plat Nomor' => $monthName,  // This will be displayed as the month header
                'Perpanjangan 1 Tahun' => '',
                'Biaya 1 Tahun' => '',
                'Perpanjangan 5 Tahun' => '',
                'Biaya 5 Tahun' => ''
            ]);

            // Now handle the actual data rows for this month
            $groupedData = $groupedData->groupBy('id_kendaraan');
            $groupedData->each(function ($vehicleData) use (&$exportData, &$nomorUrut) {
                // Initialize columns for 1 and 5 year renewals
                $perpanjangan1Tahun = '';
                $perpanjangan5Tahun = '';
                $biaya1Tahun = '';
                $biaya5Tahun = '';
                $platNomor = $vehicleData->first()->RelasiSTNKtoKendaraan->nomor_polisi ?? '';

                // Loop through each STNK entry and assign data to the correct columns
                foreach ($vehicleData as $stnk) {
                    if ($stnk->jenis_perpanjangan == '1 Tahun') {
                        $perpanjangan1Tahun = $stnk->tanggal_perpanjangan->format('d F Y');
                        $biaya1Tahun = $stnk->biaya;
                    } elseif ($stnk->jenis_perpanjangan == '5 Tahun') {
                        $perpanjangan5Tahun = $stnk->tanggal_perpanjangan->format('d F Y');
                        $biaya5Tahun = $stnk->biaya;
                    }
                }

                // Add the row to the export data collection with nomor urut
                $exportData->push([
                    'No.' => $nomorUrut++,  // Tambahkan nomor urut yang di-reset per grup bulan
                    'Plat Nomor' => $platNomor,
                    'Perpanjangan 1 Tahun' => $perpanjangan1Tahun,
                    'Biaya 1 Tahun' => $biaya1Tahun,
                    'Perpanjangan 5 Tahun' => $perpanjangan5Tahun,
                    'Biaya 5 Tahun' => $biaya5Tahun,
                ]);
            });
        }

        return $exportData;
    }




    public function headings(): array
    {
        return [
            'No.', // Kolom untuk nomor urut
            'Plat Nomor',
            'Perpanjangan 1 Tahun',
            'Biaya 1 Tahun',
            'Perpanjangan 5 Tahun',
            'Biaya 5 Tahun',
        ];
    }


    public function title(): string
    {
        return 'STNK';
    }

    public function styles(Worksheet $sheet)
    {
        // Gaya untuk heading kolom data
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'name' => 'Times New Roman',
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
        $sheet->getStyle('A1:F1')->applyFromArray($styleArray); // Sesuaikan kolom yang diperlukan (A-F)


        // Bekukan baris pertama agar sticky
        $sheet->freezePane('A2'); // Membekukan baris pertama

        // Terapkan border untuk data
        $rowCount = $sheet->getHighestRow();
        $sheet->getStyle("A2:F{$rowCount}")->applyFromArray([
            'font' => [ // Menambahkan gaya font untuk seluruh data
                'name' => 'Times New Roman', // Font untuk data
                'size' => 12, // Ukuran font untuk data
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);
        
        // Alignment tengah untuk kolom nomor urut (kolom A)
        $sheet->getStyle('A2:A' . $rowCount)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:A' . $rowCount)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);




        // Auto-size untuk kolom B sampai F
        foreach (range('B', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Format currency untuk kolom Biaya 1 Tahun (kolom D) dan Biaya 5 Tahun (kolom F)
        // Format currency Indonesia untuk kolom Biaya 1 Tahun (kolom D) dan Biaya 5 Tahun (kolom F)
        $sheet->getStyle('D2:D' . $rowCount)
            ->getNumberFormat()
            ->setFormatCode('"Rp"#,##0_-');

        $sheet->getStyle('F2:F' . $rowCount)
            ->getNumberFormat()
            ->setFormatCode('"Rp"#,##0_-');

        // Gaya untuk setiap judul pemisah bulan
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $cellValue = $sheet->getCell('B' . $rowIndex)->getValue(); // Ubah ke kolom B untuk judul bulan

            // Jika cell B berisi nama bulan (sebagai pemisah), terapkan gaya khusus
            if ($this->isMonthSeparator($cellValue)) {
                // Merge seluruh kolom B sampai F untuk baris pemisah bulan
                $sheet->mergeCells('B' . $rowIndex . ':F' . $rowIndex);
                $sheet->getStyle('B' . $rowIndex)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Ukuran lebih besar untuk pemisah bulan
                        'name' => 'Times New Roman',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER, // Center alignment
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // Tambah tinggi baris untuk pemisah bulan
                $sheet->getRowDimension($rowIndex)->setRowHeight(25);
            }
        }
    }


    /**
     * Fungsi untuk mendeteksi apakah nilai di cell adalah nama bulan sebagai pemisah
     */
    private function isMonthSeparator($value)
    {
        // Logika sederhana untuk mendeteksi apakah cell berisi nama bulan
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        foreach ($months as $month) {
            if (strpos($value, $month) !== false) {
                return true; // Jika ditemukan nama bulan dalam value, return true
            }
        }

        return false;
    }
}
