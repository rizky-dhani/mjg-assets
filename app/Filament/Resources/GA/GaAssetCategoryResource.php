<?php

namespace App\Filament\Resources\GA;

use App\Filament\Resources\GA\GaAssetCategoryResource\Pages;
use App\Models\GA\GaAssetCategory;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GaAssetCategoryResource extends Resource
{
    protected static ?string $model = GaAssetCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'General Affairs';

    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $slug = 'general-affairs/asset-categories';

    protected static ?string $navigationLabel = 'Asset Category';

    protected static ?string $modelLabel = 'Asset Category';

    protected static ?string $pluralModelLabel = 'Asset Categories';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Category Information')
                    ->schema([
                        TextInput::make('code')
                            ->label('Code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remarks')
                    ->label('Remarks')
                    ->limit(50),
                Tables\Columns\TextColumn::make('asset_count')
                    ->label('Asset Count')
                    ->getStateUsing(function ($record) {
                        return $record->assets()->count();
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make()
                    ->successNotificationTitle('Asset Category successfully updated'),
                Actions\DeleteAction::make()
                    ->successNotificationTitle('Asset Category successfully deleted'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Selected Asset Categories successfully deleted'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGaAssetCategories::route('/'),
        ];
    }
}
