<?php

namespace App\Filament\Resources\GA;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\GA\GaAssetLocation;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\GA\GaAssetLocationResource\Pages;

class GaAssetLocationResource extends Resource
{
    protected static ?string $model = GaAssetLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'General Affairs';
    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $navigationLabel = 'Asset Location';
    protected static ?string $modelLabel = 'Asset Location';
    protected static ?string $pluralModelLabel = 'Asset Locations';

    public static function canViewAny(): bool
    {
        // Only users with division can access this resource
        return auth()->user()->division && auth()->user()->division->initial === 'GA';
    }

    public static function form(Form $form): Form
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'create' => Pages\CreateGaAssetLocation::route('/create'),
            'edit' => Pages\EditGaAssetLocation::route('/{record}/edit'),
        ];
    }
}