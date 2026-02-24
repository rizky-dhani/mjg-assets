<?php

namespace App\Filament\Resources\GA;

use Filament\Actions;
use App\Filament\Resources\GA\GaAssetLocationResource\Pages;
use App\Models\GA\GaAssetLocation;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GaAssetLocationResource extends Resource
{
    protected static ?string $model = GaAssetLocation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'General Affairs';

    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $slug = 'general-affairs/asset-locations';

    protected static ?string $navigationLabel = 'Asset Location';

    protected static ?string $modelLabel = 'Asset Location';

    protected static ?string $pluralModelLabel = 'Asset Locations';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Location Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Location Name')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Location Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make()
                    ->successNotificationTitle('Asset Location successfully updated'),
                Actions\DeleteAction::make()
                    ->successNotificationTitle('Asset Location successfully deleted'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Selected Asset Location successfully deleted'),
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
            'index' => Pages\ListGaAssetLocations::route('/'),
        ];
    }
}
