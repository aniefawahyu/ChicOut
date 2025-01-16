<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExcel implements FromCollection, WithHeadings
{
    protected $data;
    protected $columns;
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($data, $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->columns;
    }
}