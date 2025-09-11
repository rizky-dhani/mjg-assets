<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\EmployeeDivision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeDivision::create([
            'divisionId' => Str::orderedUuid(),
            'abbreviation' => 'ITD',
            'name' => 'Information & Technology',
        ]);
    }
}
