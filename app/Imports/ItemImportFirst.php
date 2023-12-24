<?php

namespace App\Imports;

use App\Imports\ItemImport;
use Illuminate\Support\Collection;
use App\Interfaces\ItemRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ItemImportFirst implements WithMultipleSheets
{
   /**
     * Excel import first sheet
     *
     * @author yarzartinshwe
     *
     * @created 2023-6-26
     *
     */
    
    public function sheets(): array
    {
        return [
            0 => new ItemImport(),
        ];
    }
    
}
