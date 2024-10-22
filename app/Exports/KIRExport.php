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
        $today = Carbon::today(); // Tanggal hari ini
        $rowNumber = 1; // Inisialisasi nomor urut di sini

        KIR::with('kendaraan', 'kirHistories')
            ->when($this->year, function ($query) {
                return $query->whereHas('kirHistories', function ($subQuery) {
                    // Pastikan tahun dan bulan difilter
                    $subQuery->whereYear('tanggal_expired_kir', $this->year);

                    // Tambahkan kondisi untuk bulan
                    if ($this->month !== null) {
                        $subQuery->whereMonth('tanggal_expired_kir', $this->month);
                    }
                });
            })
            ->when($this->platNomor, function ($query) {
                return $query->whereHas('kendaraan', function ($subQuery) {
                    $subQuery->whereIn('nomor_polisi', (array) $this->platNomor);
                });
            })
            ->get()
            ->each(function ($kir) use (&$exportData, $today, &$rowNumber) {
                $platNomor = $kir->kendaraan->nomor_polisi ?? ''; // Ambil plat nomor kendaraan
                $latestHistory = $kir->kirHistories->sortByDesc('tanggal_expired_kir')->first(); // Riwayat KIR terbaru

                foreach ($kir->kirHistories as $history) {
                    $tanggalExpired = Carbon::parse($history->tanggal_expired_kir);

                    // Pastikan untuk memeriksa tahun sebelum menerapkan logika status
                    if ($this->year === null || $tanggalExpired->year == $this->year) {
                        if ($this->month === null || $tanggalExpired->month == $this->month) {


                            // Logika untuk mengatur status
                            if ($tanggalExpired < $today && $history->id == $latestHistory->id) {
                                // Jika belum diperpanjang, status menjadi 'nonaktif'
                                $status = $history->status === 'pending' ? 'pending' : 'nonaktif';
                            } else {
                                // Kosongkan status jika bukan 'pending'
                                $status = $history->status === 'pending' ? 'pending' : '';
                            }

                            // Buat baris untuk riwayat KIR
                            $exportData->push([
                                'Plat Nomor' => $platNomor,
                                'Nomor Uji KIR' => $kir->nomor_uji_kendaraan,
                                'Tanggal Perpanjangan' => $tanggalExpired, // Gunakan instance Carbon untuk pengurutan
                                'Status' => $status,
                                'Keterangan' => $history->alasan_tidak_lulus,
                                'Periode' => $history->periode,
                                'RowNumber' => $rowNumber++, // Tambahkan nomor urut di sini
                            ]);
                        }
                    }
                }
            });

        // Urutkan secara global berdasarkan 'Tanggal Perpanjangan'
        $sortedExportData = $exportData->sortBy('Tanggal Perpanjangan');

        // Format tanggal setelah pengurutan, tambahkan baris kosong antar bulan
        $previousMonth = null;
        $finalExportData = collect(); // Koleksi akhir dengan spasi
        $firstHeaderAdded = false; // Menandakan apakah header bulan pertama sudah ditambahkan
        $rowNumberPerMonth = 1; // Inisialisasi nomor urut untuk setiap bulan

        $sortedExportData->each(function ($data) use (&$previousMonth, &$finalExportData, &$firstHeaderAdded, &$rowNumberPerMonth) {
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
                $rowNumberPerMonth = 1; // Reset nomor urut untuk bulan baru
            }

            // Tambahkan baris saat ini ke koleksi akhir dengan nomor urut di atas plat nomor
            $finalExportData->push([
                'Nomor' => $rowNumberPerMonth++, // Nomor urut per bulan
                'Plat Nomor' => $data['Plat Nomor'],
                'Nomor Uji KIR' => $data['Nomor Uji KIR'],
                'Tanggal Perpanjangan' => Carbon::parse($data['Tanggal Perpanjangan'])->format('d F Y'),
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
            'Nomor',
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
        $sheet->getStyle('A1:G1')->applyFromArray($styleArray); // Sesuaikan kolom yang diperlukan (A-F)

        // Bekukan baris pertama agar sticky
        $sheet->freezePane('A2'); // Membekukan baris pertama

        // Terapkan border dan gaya font untuk data
        $rowCount = $sheet->getHighestRow();
        $sheet->getStyle("A2:G{$rowCount}")->applyFromArray([
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

        // Auto-size untuk kolom A sampai F
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Gaya untuk setiap judul pemisah bulan
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $cellValue = $sheet->getCell('A' . $rowIndex)->getValue(); // Ubah ke kolom A untuk judul bulan

            // Jika cell A berisi nama bulan (sebagai pemisah), terapkan gaya khusus
            if ($this->isMonthSeparator($cellValue)) {
                // Merge seluruh kolom A sampai F untuk baris pemisah bulan
                $sheet->mergeCells('A' . $rowIndex . ':G' . $rowIndex);
                $sheet->getStyle('A' . $rowIndex)->applyFromArray([
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
