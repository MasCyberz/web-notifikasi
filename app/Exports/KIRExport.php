<?php

namespace App\Exports;

use App\Models\KIR;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KIRExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
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

        // Ambil data KIR dengan relasi kendaraan dan kirHistories
        KIR::with('kendaraan', 'kirHistories')
            ->when($this->year, function ($query) {
                return $query->whereHas('kirHistories', function ($subQuery) {
                    $subQuery->whereYear('tanggal_expired_kir', $this->year);
                });
            })
            ->when($this->month, function ($query) {
                return $query->whereHas('kirHistories', function ($subQuery) {
                    $subQuery->whereMonth('tanggal_expired_kir', $this->month);
                });
            })
            ->when($this->platNomor, function ($query) {
                return $query->whereHas('kendaraan', function ($subQuery) {
                    $subQuery->whereIn('nomor_polisi', (array) $this->platNomor);
                });
            })
            ->get()
            ->each(function ($kir) use (&$exportData) {
                $platNomor = $kir->kendaraan->nomor_polisi ?? ''; // Ambil plat nomor kendaraan

                foreach ($kir->kirHistories as $history) {
                    // Konversi tanggal expired ke dalam instance Carbon
                    $tanggalExpired = Carbon::parse($history->tanggal_expired_kir);

                    // Buat baris untuk riwayat KIR
                    $exportData->push([
                        'Plat Nomor' => $platNomor,
                        'Nomor Uji KIR' => $kir->nomor_uji_kendaraan,
                        'Tanggal Perpanjangan' => $tanggalExpired, // Gunakan instance Carbon untuk pengurutan
                        'Status' => $history->status,
                        'Keterangan' => $history->alasan_tidak_lulus,
                        'Periode' => $history->periode,
                    ]);
                }
            });

        // Urutkan secara global berdasarkan 'Tanggal Perpanjangan'
        $sortedExportData = $exportData->sortBy('Tanggal Perpanjangan');

        // Format tanggal setelah pengurutan, tambahkan baris kosong antar bulan
        $previousMonth = null;
        $finalExportData = collect(); // Koleksi akhir dengan spasi
        $firstHeaderAdded = false; // Menandakan apakah header bulan pertama sudah ditambahkan

        $sortedExportData->each(function ($data) use (&$previousMonth, &$finalExportData, &$firstHeaderAdded) {
            $currentMonth = Carbon::parse($data['Tanggal Perpanjangan']);
            $monthYear = $currentMonth->format('m Y'); // Ambil format bulan dan tahun
            $monthName = $currentMonth->format('F Y'); // Ambil nama bulan

            // Tambahkan header bulan pertama jika belum ditambahkan
            if (!$firstHeaderAdded) {
                $finalExportData->push([
                    'Plat Nomor' => $monthName, // Header bulan
                    'Nomor Uji KIR' => '',
                    'Tanggal Perpanjangan' => '',
                    'Status' => '',
                    'Keterangan' => '',
                    'Periode' => '',
                ]);
                $firstHeaderAdded = true; // Tandai bahwa header bulan pertama sudah ditambahkan
            }

            // Tambahkan spasi jika bulan berubah
            if ($previousMonth !== null && $monthYear !== $previousMonth) {
                // Tambahkan header bulan baru
                $finalExportData->push([
                    'Plat Nomor' => $monthName, // Header bulan
                    'Nomor Uji KIR' => '',
                    'Tanggal Perpanjangan' => '',
                    'Status' => '',
                    'Keterangan' => '',
                    'Periode' => '',
                ]);
            }

            // Tambahkan baris saat ini ke koleksi akhir
            $finalExportData->push([
                'Plat Nomor' => $data['Plat Nomor'],
                'Nomor Uji KIR' => $data['Nomor Uji KIR'],
                'Tanggal Perpanjangan' => Carbon::parse($data['Tanggal Perpanjangan'])->format('d-m-Y'),
                'Status' => $data['Status'],
                'Keterangan' => $data['Keterangan'],
                'Periode' => $data['Periode'],
            ]);

            // Perbarui previousMonth
            $previousMonth = $monthYear;
        });

        return $finalExportData->values(); // Reindex dan kembalikan data export yang terurut dengan spasi
    }

    public function headings(): array
    {
        return [
            'Plat Nomor',
            'Nomor Uji KIR',
            'Tanggal Perpanjangan',
            'Status',
            'Keterangan',
            'Periode',
        ];
    }

    public function title(): string
    {
        return 'KIR';
    }

    public function styles(Worksheet $sheet)
    {
        // Gaya untuk heading
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
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

        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);

        // Menambahkan border untuk data
        $rowCount = $sheet->getHighestRow();
        $sheet->getStyle("A2:F{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Mengatur ukuran otomatis untuk kolom
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Gaya untuk setiap judul pemisah bulan
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $cellValue = $sheet->getCell('A' . $rowIndex)->getValue();

            // Jika cell A berisi nama bulan (sebagai pemisah), terapkan gaya khusus
            if ($this->isMonthSeparator($cellValue)) {
                // Merge seluruh kolom A sampai F untuk baris pemisah bulan
                $sheet->mergeCells('A' . $rowIndex . ':F' . $rowIndex);
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
            'December',
        ];

        foreach ($months as $month) {
            if (strpos($value, $month) !== false) {
                return true; // Jika ditemukan nama bulan dalam value, return true
            }
        }

        return false;
    }
}
