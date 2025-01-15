<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Jobs\ImportDataWargaJob;

class DataWargaImport implements ToModel
{

    public function model(array $row)
    {
        static $skipCount = 0;
        if ($skipCount < 2) {
            $skipCount++;
            return null;
        }

        ImportDataWargaJob::dispatch($row);

        return null;
    }
}
