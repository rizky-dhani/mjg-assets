<?php

namespace App\Filament\Resources\ITD;

use App\Traits\HasResourceRolePermissions;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\ITAsset;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ITAssetMaintenance;
use App\Models\ResourcePermission;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITD\ITAssetMaintenanceResource\Pages;
use App\Filament\Resources\ITD\ITAssetMaintenanceResource\RelationManagers;

class ITAssetMaintenanceResource extends Resource
{
    use HasResourceRolePermissions;
    protected static ?string $model = ITAssetMaintenance::class;
    protected static ?string $navigationLabel = 'Maintenance Log';
    protected static ?string $modelLabel = 'IT Asset Maintenance Log';
    protected static ?string $pluralModelLabel = 'IT Asset Maintenance Logs';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'it-asset-maintenance-log';
    protected static ?string $navigationParentItem = 'IT Assets';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = ' ITD';
    public static function getBreadcrumb(): string
    {
        return 'IT Asset Maintenance Log';
    }    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('maintenance_date')
                            ->label('Maintenance Date')
                            ->required()
                            ->default(now()),
                        Select::make('asset_id')
                            ->label('Asset Code')
                            ->relationship('asset', 'asset_code')
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $userInitial = $record->employee?->initial ?? 'N/A';
                                return "{$record->asset_code} - {$userInitial} - {$record->asset_name}";
                            })
                            ->afterStateUpdated(function ($set, $state) {
                                $asset = ITAsset::find($state);
                                if ($asset && $asset->asset_user_id) {
                                    $set('employee_id', $asset->asset_user_id);
                                    $set('initial', $asset->employee->initial ?? '');
                                } else {
                                    $set('employee_id', null);
                                    $set('initial', '');
                                }
                            })
                            ->reactive()
                            ->preload()
                            ->required()
                            ->searchable(),
                        Section::make('Employee Details')
                            ->columns(3)
                            ->schema([
                                Select::make('employee_id')
                                    ->label('User')
                                    ->relationship('employee', 'name')
                                    ->preload()
                                    ->reactive()
                                    ->searchable()
                                    ->afterStateUpdated(function ($set, $get, $state) {
                                        $employee = Employee::find($state);
                                        if ($employee) {
                                            $set('initial', $employee->initial);
                                        }
                                    })
                                    ->afterStateHydrated(function ($set, $get, $state) {
                                        $employee = Employee::find($state);
                                        if ($employee) {
                                            $set('initial', $employee->initial);
                                        }
                                    }),
                                TextInput::make('initial')
                                    ->label('Initial')
                                    ->disabled()
                                    ->afterStateHydrated(function ($set, $get) {
                                        $employee = $get('employee_id') ? Employee::find($get('employee_id')) : null;
                                        $set('initial', $employee->initial ?? '');
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function ($set, $get, $state) {
                                        $employee = Employee::find($state);
                                        $set('initial', $employee->initial ?? '');
                                    }),
                                Select::make('division_id')
                                    ->label('Division')
                                    ->relationship('division', 'name')
                                    ->preload()
                                    ->searchable(),
                            ]),
                        Textarea::make('maintenance_condition')
                            ->label('Condition/Problem')
                            ->required(),
                        Textarea::make('maintenance_repair')
                            ->label('Maintenance/Repair')
                            ->required(),
                        TimePicker::make('maintenance_start_time')
                            ->label('Start')
                            ->required()
                            ->seconds(false)
                            ->closeOnDateSelection(),
                        TimePicker::make('maintenance_end_time')
                            ->label('Finish')
                            ->required()
                            ->seconds(false)
                            ->closeOnDateSelection(),
                            ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->emptyStateHeading('No IT Asset Maintenance Logs Found')
            ->columns([
                TextColumn::make('maintenance_date')
                    ->label('Maintenance Date')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return strtoupper(Carbon::parse($state)->format('d M Y'));
                    }),
                TextColumn::make('asset.asset_code')
                    ->label('Asset Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.initial')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('division.initial')
                    ->label('Division')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this maintenance log?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Maintenance log deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these maintenance logs?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Maintenance logs deleted successfully.')
                        ->requiresConfirmation(),
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
            'index' => Pages\ListITAssetMaintenances::route('/'),
            'create' => Pages\CreateITAssetMaintenance::route('/create'),
            'edit' => Pages\EditITAssetMaintenance::route('/{record}/edit'),
        ];
    }

}
