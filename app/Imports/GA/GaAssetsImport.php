<?php

namespace App\Imports\GA;

use App\Models\GA\GaAsset;
use App\Models\GA\GaAssetCategory;
use App\Models\GA\GaAssetLocation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;


class GaAssetsImport implements ToCollection
{
    public int $headingRowNumber = 8;
    public bool $createUsageHistory = false;
    public ?int $usageLocationId = null;


    public function collection(Collection $rows): void
    {
        $headingIndex = $this->headingRowNumber - 1;
        $mergeIndex = $this->headingRowNumber;

        // Build merged heading from two rows (handles merged cells)
        $headings = [];
        if (isset($rows[$headingIndex])) {
            foreach ($rows[$headingIndex] as $col => $val) {
                $headings[$col] = trim((string) $val);
            }
        }
        if (isset($rows[$mergeIndex])) {
            foreach ($rows[$mergeIndex] as $col => $val) {
                $val = trim((string) $val);
                if ($val !== '') {
                    $headings[$col] = $val;
                }
            }
        }

        $rows->slice($this->headingRowNumber + 1)->each(function ($row) use ($headings) {
            $data = [];
            foreach ($row as $col => $val) {
                $heading = $headings[$col] ?? "col_{$col}";
                $data[$heading] = trim((string) $val);
            }

            $assetCode = $data['Inventory Code'] ?? '';
            if ($assetCode === '' || GaAsset::where('asset_code', $assetCode)->exists()) {
                return;
            }

            $itemName = $data['item_name'] ?? '';
            $manufacturer = $data['Manufacturer'] ?? '';
            $date = $data['Date'] ?? '';
            $serialNo = $data['Serial No./ Processor'] ?? '';
            $condition = $data['condition'] ?? '';
            $areaName = $data['Area'] ?? '';
            $categoryName = $data['Name'] ?? '';

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
                $location = GaAssetLocation::firstOrCreate(
                    ['name' => $areaName],
                    ['locationId' => Str::orderedUuid()]
                );
            }

            $yearBought = $this->parseYear($date);
            $mappedCondition = $this->mapCondition($condition);
            ['name' => $name, 'model' => $model] = $this->parseItemName($itemName);

            $asset = GaAsset::create([
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

            // Auto-create usage history if enabled
            if ($this->createUsageHistory && $this->usageLocationId && $location) {
                $room = \App\Models\GA\GaAssetRoom::firstOrCreate(
                    ['name' => $areaName, 'location_id' => $location->id]
                );

                \App\Models\GA\GaAssetUsageHistory::create([
                    'usageId'           => Str::orderedUuid(),
                    'asset_id'          => $asset->id,
                    'asset_location_id' => $this->usageLocationId,
                    'room_id'           => $room->id,
                    'usage_quantity'     => 1,
                    'usage_start_date'  => $this->parseDate($date) ?? now()->format('Y-m-d'),
                ]);
            }
        });
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

    private function parseDate(string $date): ?string
    {
        $date = trim($date);
        if ($date === '') {
            return null;
        }

        // Try Carbon for any common format
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseYear(string $date): int
    {
        if (preg_match('/(\d{4})/', $date, $matches)) {
            return (int) $matches[1];
        }

        return (int) now()->format('Y');
    }

    private function mapCondition(string $condition): string
    {
        $condition = strtoupper(trim($condition));

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
        $parts = preg_split('/\s+/', $itemName, -1, PREG_SPLIT_NO_EMPTY);

        if (count($parts) <= 1) {
            return ['name' => $itemName, 'model' => ''];
        }

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
