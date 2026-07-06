<?php

namespace App\Imports;

use App\Models\EmployeePosition;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class EmployeePositionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $name = $row['position'] ?? null;
        if ($name && ! EmployeePosition::where('name', $name)->exists()) {
            EmployeePosition::create([
                'positionId' => Str::orderedUuid(),
                'name' => $name,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
