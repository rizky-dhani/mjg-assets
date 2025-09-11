<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Widgets;

use App\Filament\Resources\ITD\ITAssetResource;
use App\Models\ITAsset;
use App\Models\ITAssetCategory;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ITAssetWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected ?string $heading = 'IT Assets';
    protected function getStats(): array
    {
        $stats = [];
        // Get categories with asset counts using Eloquent and sort them
        $categoriesWithCounts = ITAssetCategory::withCount([
                'assets as total_assets_count',
                'assets as in_use_assets_count' => function ($query) {
                    $query->whereNotNull('asset_user_id');
                },
                'assets as available_assets_count' => function ($query) {
                    $query->whereNull('asset_user_id');
                }
            ])
            ->orderByDesc('total_assets_count')
            ->get();

        foreach ($categoriesWithCounts as $category) {
            // Use the counted values from withCount for efficiency
            $totalAssets = $category->total_assets_count;
            $inUseAssets = $category->in_use_assets_count;
            $availableAssets = $category->available_assets_count;

            // Available stat with category in label
            $stats[] = Stat::make("{$category->name}", $availableAssets)
                ->description("Available")
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $category->id,
                        ],
                        'asset_user_id' => [
                            'available' => 'true',
                            'in_use' => 'false',
                        ],
                    ],
                ]));

            // In Use stat with category in label
            $stats[] = Stat::make("{$category->name}", $inUseAssets)
                ->description("In use")
                ->color('danger')
                ->icon('heroicon-o-user')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $category->id,
                        ],
                        'asset_user_id' => [
                            'available' => 'false',
                            'in_use' => 'true',
                        ],
                    ],
                ]));

            // Total stat with category in label
            $stats[] = Stat::make("{$category->name}", $totalAssets)
                ->description("Total")
                ->color('primary')
                ->icon('heroicon-o-computer-desktop')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $category->id,
                        ],
                    ],
                ]));
        }

        return $stats;
    }
}
