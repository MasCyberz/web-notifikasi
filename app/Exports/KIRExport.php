<?php

namespace App\Exports;

use App\Models\KIR;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
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
                $platNomor = $kir->kendaraan->nomor_polisi ?? ''; // Get the vehicle's plate number

                foreach ($kir->kirHistories as $history) {
                    // Convert the expiration date to a Carbon instance
                    $tanggalExpired = Carbon::parse($history->tanggal_expired_kir);

                    // Create a row for the KIR history
                    $exportData->push([
                        'Plat Nomor' => $platNomor,
                        'Nomor Uji KIR' => $kir->nomor_uji_kendaraan,
                        'Tanggal Perpanjangan' => $tanggalExpired, // Use raw Carbon instance for sorting
                        'Status' => $history->status,
                        'Keterangan' => $history->alasan_tidak_lulus,
                    ]);
                }
            });

        // Urutkan secara global berdasarkan 'Tanggal Perpanjangan' tanpa pengelompokan Plat Nomor
        $sortedExportData = $exportData->sortBy('Tanggal Perpanjangan');

        // Format tanggal setelah pengurutan, tambahkan baris kosong antar bulan
        $previousMonth = null;
        $finalExportData = collect(); // Final collection with spaces
        $sortedExportData->each(function ($data) use (&$previousMonth, &$finalExportData) {
            $currentMonth = Carbon::parse($data['Tanggal Perpanjangan'])->format('m-Y');

            // Add space if the month changes
            if ($previousMonth !== null && $currentMonth !== $previousMonth) {
                // Add an empty row for spacing
                $finalExportData->push([
                    'Plat Nomor' => '',
                    'Nomor Uji KIR' => '',
                    'Tanggal Perpanjangan' => '',
                    'Status' => '',
                    'Keterangan' => '',
                ]);
            }

            // Add the current row to the final collection
            $finalExportData->push([
                'Plat Nomor' => $data['Plat Nomor'],
                'Nomor Uji KIR' => $data['Nomor Uji KIR'],
                'Tanggal Perpanjangan' => Carbon::parse($data['Tanggal Perpanjangan'])->format('d-m-Y'),
                'Status' => $data['Status'],
                'Keterangan' => $data['Keterangan'],
            ]);

            // Update previousMonth
            $previousMonth = $currentMonth;
        });

        return $finalExportData->values(); // Reindex and return the sorted export data with spaces
    }

    public function headings(): array
    {
        return [
            'Plat Nomor',
            'Nomor Uji KIR',
            'Tanggal Perpanjangan',
            'Status',
            'Keterangan',
        ];
    }

    public function title(): string
    {
        return 'KIR';
    }

    public function styles(Worksheet $sheet)
    {
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

        $sheet->getStyle('A1:E1')->applyFromArray($styleArray);

        $rowCount = count($this->collection()) + 1;
        $sheet->getStyle("A2:E{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
