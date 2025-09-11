<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'user_id' => 2,
            'name' => 'Muh Ardyansyah',
            'initial' => 'ARD',
            'employee_number' => '0305'
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Febry Ulfa Rizaldo',
            'user_id' => 3,
            'initial' => 'FUR',
            'employee_number' => '0610'
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Rizky Dhani Ismail',
            'user_id' => 4,
            'initial' => 'RDI',
            'employee_number' => '0814'
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Yoga Saputra',
            'user_id' => 5,
            'initial' => 'YOG',
            'employee_number' => '0869'
        ]);
        Employee::create([
            'employeeId' => Str::orderedUuid(),
            'name' => 'Pantas Hutapea',
            'user_id' => 6,
            'initial' => 'PGH',
            'employee_number' => '0868'
        ]);
    }
}
