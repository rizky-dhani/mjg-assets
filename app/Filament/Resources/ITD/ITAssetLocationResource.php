<?php

namespace App\Filament\Resources\ITD;

use Filament\Actions;
use App\Filament\Resources\ITD\ITAssetLocationResource\Pages;
use App\Models\IT\ITAssetLocation;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ITAssetLocationResource extends Resource
{
    protected static ?string $model = ITAssetLocation::class;

    protected static ?string $slug = 'itd/asset-locations';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    protected static ?string $navigationLabel = 'Locations';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $modelLabel = 'Asset Location';

    protected static ?string $pluralModelLabel = 'Asset Locations';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Hidden::make('locationId')
                    ->default(fn () => Str::orderedUuid()->toString()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Asset Locations Found')
            ->columns([
                TextColumn::make('name')
                    ->label('Location Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this location?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Location deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these locations?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Locations deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageITAssetLocations::route('/'),
        ];
    }
}
