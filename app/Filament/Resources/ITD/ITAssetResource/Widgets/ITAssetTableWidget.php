<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Widgets;

use App\Filament\Resources\ITD\ITAssetResource;
use App\Models\IT\ITAsset;
use App\Models\IT\ITAssetCategory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ITAssetTableWidget extends TableWidget
{
    protected static ?string $heading = 'IT Assets Summary';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        // Only allow users with ITD division to see this widget
        return auth()->user()?->division?->initial === 'ITD';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // Get categories with asset counts using Eloquent and sort them
                $categoriesWithCounts = ITAssetCategory::withCount([
                    'assets as total_assets_count',
                    'assets as in_use_assets_count' => function ($query) {
                        $query->whereNotNull('asset_user_id');
                    },
                    'assets as available_assets_count' => function ($query) {
                        $query->whereNull('asset_user_id');
                    },
                ])
                ->orderByDesc('total_assets_count')
                ->get();

                // Convert to a collection to simulate a table-like structure
                return collect($categoriesWithCounts)->map(function ($category) {
                    return [
                        'category_name' => $category->name,
                        'available_count' => $category->available_assets_count,
                        'in_use_count' => $category->in_use_assets_count,
                        'total_count' => $category->total_assets_count,
                        'category_id' => $category->id,
                    ];
                });
            })
            ->columns([
                Tables\Columns\TextColumn::make('category_name')
                    ->label('Category')
                    ->weight('bold')
                    ->formatStateUsing(function ($state) {
                        return strtoupper($state);
                    }),
                
                Tables\Columns\TextColumn::make('available_count')
                    ->label('Available')
                    ->color('success')
                    ->url(fn ($record) => ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['category_id'],
                            ],
                            'asset_user_id' => [
                                'available' => 'true',
                                'in_use' => 'false',
                            ],
                        ],
                    ]))
                    ->formatStateUsing(function ($state) {
                        return $state . ' assets';
                    })
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('in_use_count')
                    ->label('In Use')
                    ->color('danger')
                    ->url(fn ($record) => ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['category_id'],
                            ],
                            'asset_user_id' => [
                                'available' => 'false',
                                'in_use' => 'true',
                            ],
                        ],
                    ]))
                    ->formatStateUsing(function ($state) {
                        return $state . ' assets';
                    })
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total')
                    ->color('primary')
                    ->url(fn ($record) => ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['category_id'],
                            ],
                        ],
                    ]))
                    ->formatStateUsing(function ($state) {
                        return $state . ' assets';
                    })
                    ->weight('semibold'),
            ])
            ->paginated(false)
            ->striped();
    }
}