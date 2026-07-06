<?php

namespace Database\Seeders;

use App\Models\ITAssetLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ITAssetLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ITAssetLocation::insert([
            ['locationId' => Str::orderedUuid(), 'name' => 'Head Office'],
            ['locationId' => Str::orderedUuid(), 'name' => 'Head Office - Matraman'],
            ['locationId' => Str::orderedUuid(), 'name' => 'Gudang - Bizpark 1'],
            ['locationId' => Str::orderedUuid(), 'name' => 'Gudang - Bizpark 2'],
        ]);
    }
}
