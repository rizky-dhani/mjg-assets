<?php

namespace App\Filament\Resources\GA;

use App\Filament\Resources\GA\GaAssetRoomResource\Pages;
use App\Models\GA\GaAssetRoom;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GaAssetRoomResource extends Resource
{
    protected static ?string $model = GaAssetRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'General Affairs';

    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $slug = 'general-affairs/asset-rooms';

    protected static ?int $sort = 2;

    protected static ?string $navigationLabel = 'Rooms';

    protected static ?string $modelLabel = 'Room';

    protected static ?string $pluralModelLabel = 'Rooms';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Room Information')
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
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle('Room successfully updated'),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Room successfully deleted'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
