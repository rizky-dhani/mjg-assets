<?php

namespace App\Filament\Resources\GA;

use Filament\Actions;
use App\Filament\Resources\GA\GaAssetUsageHistoryResource\Pages;
use App\Models\GA\GaAssetUsageHistory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class GaAssetUsageHistoryResource extends Resource
{
    protected static ?string $model = GaAssetUsageHistory::class;

    protected static ?string $slug = 'general-affairs/usage-histories';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|\UnitEnum|null $navigationGroup = 'General Affairs';

    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $navigationLabel = 'Usage History';

    protected static ?string $modelLabel = 'Asset Usage History';

    protected static ?string $pluralModelLabel = 'Asset Usage Histories';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Usage Information')
                    ->schema([
                        Select::make('asset_id')
                            ->relationship('asset', 'asset_name')
                            ->required(),
                        Select::make('asset_location_id')
                            ->relationship('location', 'name'),
                        Select::make('employee_id')
                            ->relationship('employee', 'name'),
                        Select::make('department_id')
                            ->relationship('department', 'name'),
                        Select::make('division_id')
                            ->relationship('division', 'name'),
                        Select::make('position_id')
                            ->relationship('position', 'name'),
                        DatePicker::make('usage_start_date')
                            ->label('Start Date')
                            ->required(),
                        DatePicker::make('usage_end_date')
                            ->label('End Date'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.asset_name')
                    ->label('Asset')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Employee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->label('Division')
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name')
                    ->label('Position')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make()
                    ->successNotificationTitle('Asset Usage History successfully updated'),
                Actions\DeleteAction::make()
                    ->successNotificationTitle('Asset Usage History successfully deleted'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->successNotificationTitle('Selected Asset Usage History successfully deleted'),
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
            'index' => Pages\ListGaAssetUsageHistories::route('/'),
        ];
    }
}
