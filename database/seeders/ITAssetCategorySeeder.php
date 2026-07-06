<?php

namespace Database\Seeders;

use App\Models\ITAssetCategory;
use Illuminate\Database\Seeder;

class ITAssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ITAssetCategory::insert([
            [
                'code' => '001',
                'name' => 'SERVER',
                'remarks' => "CPU : \nRAM : \nStorage : \nOS : ",
            ],
            [
                'code' => '002',
                'name' => 'LAPTOP',
                'remarks' => "CPU : \nRAM : \nStorage : ",
            ],
            [
                'code' => '003',
                'name' => 'PC',
                'remarks' => "CPU : \nRAM : \nStorage : \nOS : ",
            ],
            [
                'code' => '004',
                'name' => 'ACCESSORIES',
                'remarks' => null,
            ],
            [
                'code' => '005',
                'name' => 'PRINTER',
                'remarks' => null,
            ],
            [
                'code' => '006',
                'name' => 'SCANNER',
                'remarks' => null,
            ],

        ]);
    }
}
