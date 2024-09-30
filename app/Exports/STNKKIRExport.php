<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class STNKKIRExport implements WithMultipleSheets
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

    public function sheets(): array
    {
        return[
            'STNK' => new STNKExport($this->year, $this->month, $this->platNomor),
            'KIR' => new KIRExport($this->year, $this->month, $this->platNomor),
        ];
    }
}
