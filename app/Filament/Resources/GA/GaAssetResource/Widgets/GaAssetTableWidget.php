<?php

namespace App\Filament\Resources\GA\GaAssetResource\Widgets;

use App\Filament\Resources\GA\GaAssetResource;
use App\Models\GA\GaAsset;
use App\Models\GA\GaAssetCategory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class GaAssetTableWidget extends TableWidget
{
    protected static ?string $heading = 'GA Assets Summary';

    // protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        // Only allow users with ITD division to see this widget
        return auth()->user()?->division?->initial === 'GA';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GaAssetCategory::query()
                    ->select('id', 'name')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category')
                    ->weight('bold')
                    ->formatStateUsing(function ($state) {
                        return strtoupper($state);
                    }),

                Tables\Columns\TextColumn::make('available_count')
                    ->label('Available')
                    ->color('success')
                    ->getStateUsing(function (GaAssetCategory $record) {
                        $availableCount = GaAsset::where('asset_category_id', $record->id)
                            ->whereNull('asset_user_id')
                            ->count();

                        return $availableCount;
                    })
                    ->url(fn ($record) => GaAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['id'],
                            ],
                            'asset_user_id' => [
                                'available' => 'true',
                                'in_use' => 'false',
                            ],
                        ],
                    ])),

                Tables\Columns\TextColumn::make('in_use_count')
                    ->label('In Use')
                    ->color('danger')
                    ->getStateUsing(function (GaAssetCategory $record) {
                        $inUseCount = GaAsset::where('asset_category_id', $record->id)
                            ->whereNotNull('asset_user_id')
                            ->count();

                        return $inUseCount;
                    })
                    ->url(fn ($record) => GaAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['id'],
                            ],
                            'asset_user_id' => [
                                'available' => 'false',
                                'in_use' => 'true',
                            ],
                        ],
                    ])),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total')
                    ->color('primary')
                    ->getStateUsing(function (GaAssetCategory $record) {
                        $totalCount = GaAsset::where('asset_category_id', $record->id)
                            ->count();

                        return $totalCount;
                    })
                    ->url(fn ($record) => GaAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['id'],
                            ],
                        ],
                    ])),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('assets_count', 'desc');
    }
}
