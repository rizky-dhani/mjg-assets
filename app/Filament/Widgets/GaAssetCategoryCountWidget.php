<?php

namespace App\Filament\Widgets;

use App\Models\GA\GaAsset;
use App\Models\GA\GaAssetCategory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class GaAssetCategoryCountWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                GaAssetCategory::query()
                    ->withCount('assets')
                    ->has('assets') // Only show categories that have assets
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Category Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assets_count')
                    ->label('Asset Count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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