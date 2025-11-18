<?php

namespace App\Filament\Resources\GA;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use App\Models\GA\GaAssetMaintenance;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use App\Filament\Resources\GA\GaAssetMaintenanceResource\Pages;

class GaAssetMaintenanceResource extends Resource
{
    protected static ?string $model = GaAssetMaintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'General Affairs';
    protected static ?string $navigationParentItem = 'Assets';

    protected static ?string $navigationLabel = 'Asset Maintenance';
    protected static ?string $modelLabel = 'Asset Maintenance';
    protected static ?string $pluralModelLabel = 'Asset Maintenances';

    public static function canViewAny(): bool
    {
        // Only users with division can access this resource
        return auth()->user()->division && auth()->user()->division->initial === 'GA';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Maintenance Information')
                    ->schema([
                        DatePicker::make('maintenance_date')
                            ->label('Maintenance Date')
                            ->required(),
                        Select::make('asset_id')
                            ->relationship('asset', 'asset_name')
                            ->required(),
                        Select::make('employee_id')
                            ->relationship('employee', 'name'),
                        Select::make('division_id')
                            ->relationship('division', 'name'),
                        TextInput::make('maintenance_condition')
                            ->label('Condition')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('maintenance_repair')
                            ->label('Repair')
                            ->required()
                            ->maxLength(255),
                        TimePicker::make('maintenance_start_time')
                            ->label('Start Time')
                            ->required(),
                        TimePicker::make('maintenance_end_time')
                            ->label('End Time')
                            ->required(),
                        Select::make('pic_id')
                            ->label('PIC')
                            ->relationship('user', 'name')
                            ->required(),
                        Select::make('reviewer_id')
                            ->label('Reviewer')
                            ->relationship('reviewer', 'name'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asset.asset_name')
                    ->label('Asset')
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Employee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->label('Division')
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenance_condition')
                    ->label('Condition')
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenance_repair')
                    ->label('Repair')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('PIC')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewer')
                    ->sortable(),
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
            'index' => Pages\ListGaAssetMaintenances::route('/'),
            'create' => Pages\CreateGaAssetMaintenance::route('/create'),
            'edit' => Pages\EditGaAssetMaintenance::route('/{record}/edit'),
        ];
    }
}