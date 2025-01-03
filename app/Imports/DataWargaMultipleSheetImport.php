<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataWargaMultipleSheetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new DataWargaImport(), // Index 0 untuk sheet pertama
        ];
    }
}
