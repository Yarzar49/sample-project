<?php

namespace App\Exports;
use App\Exports\CategoryNameExport;
use App\Exports\ItemRegistrationExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

// use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelRegistration implements WithMultipleSheets
{
    /*
     * Item registration with two sheets
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-23
     *
     */

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new ItemRegistrationExport();
        $sheets[] = new CategoryNameExport();
        return $sheets;
    }
}
