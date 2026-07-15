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
        $assetCode = trim($row['asset_code'] ?? '');
        $itemName = trim($row['item_name'] ?? '');
        $manufacturer = trim($row['manufacturer'] ?? '');
        $serialNo = trim($row['serial_no'] ?? '');
        $date = trim($row['date'] ?? '');
        $condition = trim($row['condition'] ?? '');

        // Extract category code from asset_code (format: MJG-INV-HCG.05-00-{categoryCode}-{autoIncrement})
        $categoryCode = $this->extractCategoryCode($assetCode);

        // Lookup or skip if category not found
        $category = $categoryCode
            ? GaAssetCategory::where('code', $categoryCode)->first()
            : null;

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
            'asset_category_id'  => $category?->id,
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
        if (preg_match('/MJG-INV-HCG\.05-00-(\d{3})-\d{2}/', $assetCode, $matches)) {
            return $matches[1];
        }

        return null;
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
