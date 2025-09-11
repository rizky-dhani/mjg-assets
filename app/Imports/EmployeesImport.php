<?php

namespace App\Imports;

use Str;
use App\Models\Employee;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class EmployeesImport implements ToModel, WithHeadingRow
{    
    public function model(array $row)
    {
        $name = $row['name'] ?? null;
        $initial = $row['initial'] ?? null;
        $employee_number = $row['employee_number'] ?? null;
        // Only import if employee_number and initial do not exist and all required fields are present
        if (
            $employee_number && $name && $initial &&
            !Employee::where('employee_number', $employee_number)->exists() &&
            !Employee::where('initial', $initial)->exists()
        ) {
            Employee::create([
                'employeeId' => Str::orderedUuid(),
                'name' => $name,
                'initial' => $initial,
                'employee_number' => $employee_number,
            ]);
        }
    }
    
    public function headingRow(): int
    {
        return 1;
    }
}
