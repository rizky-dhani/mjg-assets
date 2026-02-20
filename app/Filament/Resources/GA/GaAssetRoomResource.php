<?php

namespace App\Filament\Resources\GA;

use App\Filament\Resources\GA\GaAssetRoomResource\Pages;
use App\Models\GA\GaAssetRoom;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GaAssetRoomResource extends Resource
{
    protected static ?string $model = GaAssetRoom::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string|\UnitEnum|null $navigationGroup = 'General Affairs';

    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $slug = 'general-affairs/asset-rooms';

    protected static ?int $sort = 2;

    protected static ?string $navigationLabel = 'Asset Rooms';

    protected static ?string $modelLabel = 'Asset Room';

    protected static ?string $pluralModelLabel = 'Asset Rooms';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Room Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Room Name')
                            ->required()
                            ->maxLength(255),
                        Select::make('location_id')
                            ->label('Location')
                            ->relationship('location', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Room Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make()
                    ->successNotificationTitle('Room successfully updated'),
                Actions\DeleteAction::make()
                    ->successNotificationTitle('Room successfully deleted'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Selected rooms successfully deleted'),
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
            'index' => Pages\ListGaAssetRooms::route('/'),
        ];
    }
}
