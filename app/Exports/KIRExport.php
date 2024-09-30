<?php

namespace App\Exports;

use App\Models\KIR;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KIRExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $year;
    protected $month;
    protected $platNomor;
    // protected $judulYear;

    public function __construct($year = null, $month = null, $platNomor = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->platNomor = $platNomor;
        // Set judulYear ke tahun saat ini jika tidak ada input tahun
        // $this->judulYear = $year ?? Carbon::now()->year;
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
                        'Tanggal Perpanjangan' => $tanggalExpired->format('d-m-Y'), // Format date for display
                        'Status' => $history->status,
                        'Keterangan' => $history->alasan_tidak_lulus,
                    ]);
                }

                // Add an empty row only if there are more entries to follow
                if ($kir->kirHistories->isNotEmpty() && !$exportData->isEmpty() && $exportData->last()['Plat Nomor'] !== $platNomor) {
                    $exportData->push([
                        'Plat Nomor' => '',
                        'Nomor Uji KIR' => '',
                        'Tanggal Perpanjangan' => '',
                        'Status' => '',
                        'Keterangan' => '',
                    ]);
                }
            });

        // Group by 'Plat Nomor'
        $groupedData = $exportData->groupBy('Plat Nomor');

        $sortedExportData = collect(); // New collection to hold sorted data

        // Iterate through each group and sort items by 'Tanggal Perpanjangan'
        foreach ($groupedData as $platNomor => $items) {
            // Sort the items within the group by 'Tanggal Perpanjangan'
            $sortedItems = $items->sortBy(function ($item) {
                return Carbon::parse($item['Tanggal Perpanjangan']);
            });

            // Add sorted items to the new collection
            $sortedExportData = $sortedExportData->merge($sortedItems);

            // Add an empty row after each group
            $sortedExportData->push([
                'Plat Nomor' => '',
                'Nomor Uji KIR' => '',
                'Tanggal Perpanjangan' => '',
                'Status' => '',
                'Keterangan' => '',
            ]);
        }

        // Reformat the 'Tanggal Perpanjangan' after sorting
        $sortedExportData = $sortedExportData->map(function ($data) {
            if ($data['Tanggal Perpanjangan'] instanceof Carbon) {
                $data['Tanggal Perpanjangan'] = $data['Tanggal Perpanjangan']->format('d-m-Y');
            }
            return $data;
        });

        return $sortedExportData->values(); // Reindex and return the sorted export data
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
        $sheet->getStyle('A1:E1')->applyFromArray($styleArray);

        // Apply border styles to data rows
        $rowCount = count($this->collection()) + 1; // +1 for the heading row
        $sheet->getStyle("A2:E{$rowCount}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Auto-size columns A to E
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
