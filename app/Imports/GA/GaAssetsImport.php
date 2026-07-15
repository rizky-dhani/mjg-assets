<?php

namespace App\Imports\GA;

use App\Models\GA\GaAsset;
use App\Models\GA\GaAssetCategory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class GaAssetsImport implements ToModel, WithHeadingRow
{
    public function model(array $row): GaAsset
    {
        $categoryCode = $row['category'] ?? null;
        $category = $categoryCode
            ? GaAssetCategory::where('code', $categoryCode)->first()
            : null;

        $price = $this->parseCurrency($row['price'] ?? null);
        $sellPrice = $this->parseCurrency($row['sell_price'] ?? null);

        return new GaAsset([
            'assetId'            => Str::orderedUuid(),
            'asset_name'         => strtoupper($row['name'] ?? ''),
            'asset_code'         => null, // generated later if needed
            'asset_category_id'  => $category?->id,
            'asset_year_bought'  => $row['year_bought'] ?? now()->format('Y'),
            'asset_brand'        => strtoupper($row['brand'] ?? ''),
            'asset_model'        => strtoupper($row['model'] ?? ''),
            'asset_serial_number'=> strtoupper($row['serial_number'] ?? ''),
            'asset_condition'    => $row['condition'] ?? 'New',
            'asset_price'        => $price,
            'asset_sell_price'   => $sellPrice,
            'asset_notes'        => $row['notes'] ?? null,
            'asset_remarks'      => strtoupper($row['remarks'] ?? ''),
            'asset_location_id'  => null,
            'asset_user_id'      => null,
            'pic_id'             => auth()->id(),
            'barcode'            => null,
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }

    private function parseCurrency(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}
