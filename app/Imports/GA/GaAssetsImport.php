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
        $assetCode = trim($row['asset_code'] ?? '');
        $itemName = trim($row['item_name'] ?? '');
        $manufacturer = trim($row['manufacturer'] ?? '');
        $serialNo = trim($row['serial_no'] ?? '');
        $date = trim($row['date'] ?? '');
        $condition = trim($row['condition'] ?? '');

        // 1. Priority: lookup by extracted category code
        $categoryCode = $this->extractCategoryCode($assetCode);
        $sheetCategoryName = trim($row['category_name'] ?? $row['category'] ?? $row['name'] ?? $row['Name'] ?? '');

        $category = $categoryCode
            ? GaAssetCategory::where('code', $categoryCode)->first()
            : null;

        // 2. Fallback: lookup by sheet category name
        if (! $category && $sheetCategoryName !== '') {
            $category = GaAssetCategory::where('name', $sheetCategoryName)->first();
        }

        // 3. Create new category if both lookups failed
        if (! $category) {
            $code = $categoryCode ?? $this->generateCategoryCode();
            $name = $sheetCategoryName !== ''
                ? $sheetCategoryName
                : strtoupper(explode(' ', trim($itemName))[0] ?: 'UNCATEGORIZED');

            $category = GaAssetCategory::firstOrCreate(
                ['name' => $name],
                ['code' => $code]
            );
        }

        // Parse year from date (format: "06 APR 2026" or "2026")
        $yearBought = $this->parseYear($date);

        // Map condition: A=Active→New, or use as-is if matches enum
        $mappedCondition = $this->mapCondition($condition);

        // Parse item name: "Air Conditioner GWC-18N" → name="AIR CONDITIONER", model="GWC-18N"
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
            'asset_location_id'  => null,
            'asset_user_id'      => null,
            'pic_id'             => auth()->id(),
            'barcode'            => null,
        ]);
    }

    public function headingRow(): int
    {
        return 9;
    }

    private function extractCategoryCode(string $assetCode): ?string
    {
        // Format: MJG-INV-HCG.05-00-{categoryCode}-{autoIncrement}
        // e.g., MJG-INV-HCG.05-00-001-01 → categoryCode = "001"
        if (preg_match('/MJG-INV-HCG\.05-00-(\d+)-\d+/', $assetCode, $matches)) {
            return $matches[1];
        }

        return null;
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
