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
    public function model(array $row): ?GaAsset
    {
        $assetCode = trim($row['Inventory Code'] ?? '');
        if ($assetCode === '') {
            return null;
        }

        $itemName = trim($row['item_name'] ?? '');
        $manufacturer = trim($row['Manufacturer'] ?? '');
        $serialNo = trim($row['Serial No./ Processor'] ?? '');
        $date = trim($row['date'] ?? '');
        $condition = trim($row['condition'] ?? '');
        $areaName = trim($row['Area'] ?? '');
        $categoryName = trim($row['Name'] ?? '');

        // Category: lookup by name, create if not exists
        $category = $categoryName !== ''
            ? GaAssetCategory::firstOrCreate(
                ['name' => $categoryName],
                ['code' => $this->generateCategoryCode()]
            )
            : GaAssetCategory::firstOrCreate(
                ['name' => 'UNCATEGORIZED'],
                ['code' => $this->generateCategoryCode()]
            );

        // Location: lookup by name, create if not exists
        $location = null;
        if ($areaName !== '') {
            $location = \App\Models\GA\GaAssetLocation::firstOrCreate(
                ['name' => $areaName]
            );
        }

        $yearBought = $this->parseYear($date);
        $mappedCondition = $this->mapCondition($condition);
        ['name' => $name, 'model' => $model] = $this->parseItemName($itemName);

        return new GaAsset([
            'assetId'            => Str::orderedUuid(),
            'asset_name'         => strtoupper($name),
            'asset_code'         => $assetCode,
            'asset_category_id'  => $category->id,
            'asset_year_bought'  => $yearBought,
            'asset_brand'        => strtoupper($manufacturer),
            'asset_model'        => strtoupper($model),
            'asset_serial_number'=> strtoupper($serialNo ?: '0000'),
            'asset_condition'    => $mappedCondition,
            'asset_price'        => null,
            'asset_sell_price'   => null,
            'asset_notes'        => null,
            'asset_remarks'      => null,
            'asset_location_id'  => $location?->id,
            'asset_user_id'      => null,
            'pic_id'             => auth()->id(),
            'barcode'            => null,
        ]);
    }

    public function headingRow(): int
    {
        return 9;
    }


    private function generateCategoryCode(): string
    {
        $maxCode = GaAssetCategory::select('code')
            ->whereRaw('code REGEXP ?', ['^[0-9]+$'])
            ->orderByRaw('CAST(code AS UNSIGNED) DESC')
            ->value('code');

        $next = $maxCode ? ((int) $maxCode + 1) : 1;

        return str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    private function parseYear(string $date): int
    {
        // Format: "06 APR 2026" → 2026
        if (preg_match('/(\d{4})/', $date, $matches)) {
            return (int) $matches[1];
        }

        return (int) now()->format('Y');
    }

    private function mapCondition(string $condition): string
    {
        $condition = strtoupper(trim($condition));

        // Map common codes to enum values
        return match ($condition) {
            'A', 'ACTIVE', 'NEW', 'BARU' => 'New',
            'FH', 'FIRST HAND' => 'First Hand',
            'U', 'USED', 'BEKAS' => 'Used',
            'D', 'DEFECT', 'RUSAK' => 'Defect',
            'DIS', 'DISPOSED' => 'Disposed',
            default => 'New',
        };
    }

    private function parseItemName(string $itemName): array
    {
        // "Air Conditioner GWC-18N" → name="Air Conditioner", model="GWC-18N"
        // "Kursi Kerja" → name="Kursi Kerja", model=""
        // "Pesawat Telefon KX-TS505" → name="Pesawat Telefon", model="KX-TS505"

        $parts = preg_split('/\s+/', $itemName, -1, PREG_SPLIT_NO_EMPTY);

        if (count($parts) <= 1) {
            return ['name' => $itemName, 'model' => ''];
        }

        // Known model patterns (alphanumeric with hyphens/dots)
        $modelPatterns = [
            '/^[A-Z0-9][\w.-]*$/i', // Alphanumeric model codes
        ];

        // Try to find where model starts (last part that looks like a model number)
        $nameParts = [];
        $modelParts = [];
        $modelFound = false;

        for ($i = count($parts) - 1; $i >= 0; $i--) {
            $part = $parts[$i];
            if (! $modelFound && preg_match('/^[A-Z0-9][\w.-]*$/i', $part) && strlen($part) > 1) {
                array_unshift($modelParts, $part);
            } else {
                $modelFound = true;
                array_unshift($nameParts, $part);
            }
        }

        // If no model found, treat last word as model if it looks like one
        if (empty($modelParts) && count($parts) > 1) {
            $lastPart = end($parts);
            if (preg_match('/[A-Z0-9]{2,}/i', $lastPart)) {
                $modelParts = [$lastPart];
                array_pop($nameParts);
            }
        }

        return [
            'name' => implode(' ', $nameParts),
            'model' => implode(' ', $modelParts),
        ];
    }
}
