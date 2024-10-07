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
            // Group data by month number (1 for January, 2 for February, etc.)
            ->groupBy(function ($item) {
                return $item->tanggal_perpanjangan->format('n Y'); // Group by month number and year
            })
            // Sort the groups by month number
            ->sortBy(function ($group, $key) {
                [$month, $year] = explode(' ', $key); // Extract month and year
                return sprintf('%04d-%02d', $year, $month); // Create sortable string by year-month
            });

        // Loop through each month group and build the data
        foreach ($stnks as $monthYear => $groupedData) {
            // Get the name of the month (January, February, etc.)
            $monthName = Carbon::createFromFormat('n Y', $monthYear)->format('F Y');

            // Add a header for the month (empty values for merging cells)
            $exportData->push([
                'Plat Nomor' => $monthName,  // This will be displayed as the month header
                'Perpanjangan 1 Tahun' => '',
                'Biaya 1 Tahun' => '',
                'Perpanjangan 5 Tahun' => '',
                'Biaya 5 Tahun' => ''
            ]);

            // Now handle the actual data rows for this month
            $groupedData = $groupedData->groupBy('id_kendaraan');
            $groupedData->each(function ($vehicleData) use (&$exportData) {
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
                        $biaya1Tahun = "'". $stnk->biaya;
                    } elseif ($stnk->jenis_perpanjangan == '5 Tahun') {
                        $perpanjangan5Tahun = $stnk->tanggal_perpanjangan->format('d F Y');
                        $biaya5Tahun = "'". $stnk->biaya;
                    }
                }

                // Add the row to the export data collection
                $exportData->push([
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
        $sheet->getStyle('A1:E1')->applyFromArray($styleArray); // Sesuaikan kolom yang diperlukan (A-E)

        // Terapkan border untuk data
        $rowCount = $sheet->getHighestRow();
        $sheet->getStyle("A2:E{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Auto-size untuk kolom A sampai E
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Gaya untuk setiap judul pemisah bulan
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $cellValue = $sheet->getCell('A' . $rowIndex)->getValue();

            // Jika cell A berisi nama bulan (sebagai pemisah), terapkan gaya khusus
            if ($this->isMonthSeparator($cellValue)) {
                // Merge seluruh kolom A sampai E untuk baris pemisah bulan
                $sheet->mergeCells('A' . $rowIndex . ':E' . $rowIndex);
                $sheet->getStyle('A' . $rowIndex)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Ukuran lebih besar untuk pemisah bulan
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
