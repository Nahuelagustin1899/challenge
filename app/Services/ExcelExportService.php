<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelExportService implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private $data;
    private $headings;
    private $mapFunction;

    public function __construct($data, $headings, $mapFunction = null)
    {
        $this->data = $data;
        $this->headings = $headings;
        $this->mapFunction = $mapFunction;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($row): array
    {
        if ($this->mapFunction) {
            return call_user_func($this->mapFunction, $row);
        }
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function export(string $fileName)
    {
        return Excel::download($this, $fileName);
    }
}
