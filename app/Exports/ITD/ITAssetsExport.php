<?php

namespace App\Exports\ITD;

use App\Models\IT\ITAsset;
use App\Models\IT\ITAssetCategory;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ITAssetsExport implements WithMultipleSheets
{
    protected array $ids;

    protected array $categoryIds;

    public function __construct(array $ids = [], array $categoryIds = [])
    {
        $this->ids = $ids;
        $this->categoryIds = $categoryIds;
    }

    public function sheets(): array
    {
        if (! empty($this->ids)) {
            // Bulk action: determine categories from selected assets
            $categoryIds = ITAsset::whereIn('id', $this->ids)
                ->distinct()
                ->pluck('asset_category_id');
        } elseif (! empty($this->categoryIds)) {
            $categoryIds = $this->categoryIds;
        } else {
            // No filter: export all categories that have assets
            $categoryIds = ITAsset::distinct()->pluck('asset_category_id');
        }

        $categories = ITAssetCategory::whereIn('id', $categoryIds)->get();

        return $categories->map(fn (ITAssetCategory $category) => new ITAssetCategorySheet($category, $this->ids))->values()->toArray();
    }
}
