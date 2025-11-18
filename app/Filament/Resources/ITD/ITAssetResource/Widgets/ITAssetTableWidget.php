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
    //protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        // Only allow users with ITD division to see this widget
        return auth()->user()?->division?->initial === 'ITD';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ITAssetCategory::query()
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
                    ->getStateUsing(function (ITAssetCategory $record) {
                        $availableCount = ITAsset::where('asset_category_id', $record->id)
                            ->whereNull('asset_user_id')
                            ->count();
                        return $availableCount;
                    })
                    ->url(fn ($record) => ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['id'],
                            ],
                            'asset_user_id' => [
                                'available' => 'true',
                                'in_use' => 'false',
                            ],
                        ],
                    ]))
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('in_use_count')
                    ->label('In Use')
                    ->color('danger')
                    ->getStateUsing(function (ITAssetCategory $record) {
                        $inUseCount = ITAsset::where('asset_category_id', $record->id)
                            ->whereNotNull('asset_user_id')
                            ->count();
                        return $inUseCount;
                    })
                    ->url(fn ($record) => ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['id'],
                            ],
                            'asset_user_id' => [
                                'available' => 'false',
                                'in_use' => 'true',
                            ],
                        ],
                    ]))
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total')
                    ->color('primary')
                    ->getStateUsing(function (ITAssetCategory $record) {
                        $totalCount = ITAsset::where('asset_category_id', $record->id)
                            ->count();
                        return $totalCount;
                    })
                    ->url(fn ($record) => ITAssetResource::getUrl('index', [
                        'tableFilters' => [
                            'category_id' => [
                                'value' => $record['id'],
                            ],
                        ],
                    ]))
                    ->weight('semibold'),
            ])
            ->paginated(false)
            ->striped();
    }
}