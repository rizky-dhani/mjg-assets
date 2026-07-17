<?php

namespace Database\Seeders;

use App\Models\Employee\EmployeeDivision;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeDivision::create([
            'divisionId' => Str::orderedUuid(),
            'initial' => 'ITD',
            'name' => 'Information & Technology',
        ]);
    }
}
