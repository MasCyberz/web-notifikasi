<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\STNK;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
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

        STNK::with('RelasiSTNKtoKendaraan')
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
            ->each(function ($stnk) use (&$exportData) {
                $platNomor = $stnk->RelasiSTNKtoKendaraan->nomor_polisi ?? ''; // Get the vehicle's plate number

                // Create a row for the STNK entry
                $exportData->push([
                    'Plat Nomor' => $platNomor,
                    'Tanggal Perpanjangan' => $stnk->tanggal_perpanjangan->format('d-m-Y'), // Format date for display
                    'Jenis Perpanjangan' => $stnk->jenis_perpanjangan,
                    'Biaya' => $stnk->biaya,
                ]);

                // Add an empty row only if there are more entries to follow
                if (!$exportData->isEmpty() && $exportData->last()['Plat Nomor'] !== $platNomor) {
                    $exportData->push([
                        'Plat Nomor' => '',
                        'Tanggal Perpanjangan' => '',
                        'Jenis Perpanjangan' => '',
                        'Biaya' => '',
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
                'Tanggal Perpanjangan' => '',
                'Jenis Perpanjangan' => '',
                'Biaya' => '',
            ]);
        }

        return $sortedExportData->values(); // Reindex and return the sorted export data
    }

    public function headings(): array
    {
        return [
            'Plat Nomor',
            'Tanggal Perpanjangan',
            'Jenis Perpanjangan',
            'Biaya',
        ];
    }

    public function title(): string{
        return 'STNK';
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
                    'argb' => 'B7DEE8', // Desired fill color
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
        $sheet->getStyle('A1:D1')->applyFromArray($styleArray); // Change E1 to D1 since you have 4 headings

        // Apply border styles to data rows
        $rowCount = count($this->collection()) + 1; // +1 for the heading row
        $sheet->getStyle("A2:D{$rowCount}")->applyFromArray([ // Change E to D
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ]);

        // Auto-size columns A to D
        foreach (range('A', 'D') as $column) { // Change E to D
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
