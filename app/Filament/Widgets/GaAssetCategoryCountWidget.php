<?php

namespace App\Filament\Widgets;

use App\Models\GA\GaAssetCategory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class GaAssetCategoryCountWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        // Show only if user is logged in and has division initial 'GA'
        return $user && $user->division && $user->division->initial === 'GA';
    }

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
