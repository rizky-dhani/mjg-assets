<?php

namespace App\Filament\Resources\ITD;

use App\Filament\Resources\ITD\ITAssetUsageHistoryResource\Pages;
use App\Models\IT\ITAssetUsageHistory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ITAssetUsageHistoryResource extends Resource
{
    protected static ?string $model = ITAssetUsageHistory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = ' ITD';

    protected static ?string $navigationParentItem = 'Assets';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    protected static ?string $navigationLabel = 'Usage History';

    protected static ?string $modelLabel = 'Asset Usage History';

    protected static ?string $pluralModelLabel = 'Asset Usage Histories';

    protected static ?string $slug = 'itd/asset-usage-histories';

    protected static ?int $navigationSort = 1;

    public static function getBreadcrumb(): string
    {
        return 'Asset Usage History';
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('employee_id')
                    ->label('Assign To...')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' - '.$record->name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('asset_location_id')
                    ->label('Location')
                    ->relationship('location', 'name', fn ($query) => $query->orderBy('created_at', 'asc'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('usage_start_date')
                    ->label('Start Date')
                    ->default(now())
                    ->required(),
                DatePicker::make('usage_end_date')
                    ->label('Usage End Date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No Asset Usage History Found')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('asset.asset_code')
                    ->label('Asset Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asset.asset_name')
                    ->label('Asset Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('location.name')
                    ->label('Location')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label('Assigned To')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name),
                SelectFilter::make('employee_id')
                    ->label('Assigned To')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' - '.$record->name),
            ])
            ->actions([
                Actions\Action::make('view_asset')
                    ->label('View Asset')
                    ->icon('heroicon-o-eye')
                    ->color('dark')
                    ->url(fn ($record) => route('filament.admin.resources.it-assets.view', ['record' => $record->asset->assetId])),
                // ->openUrlInNewTab(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this usage history?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Usage history deleted successfully.')
                    ->requiresConfirmation()
                    ->before(function ($record) {
                        // Before deleting, set asset_user_id to the employee_id of the latest previous usage history (if any)
                        if ($record->asset) {
                            $previousUsage = $record->asset
                                ->usageHistory()
                                ->where('id', '<', $record->id)
                                ->orderByDesc('usage_end_date')
                                ->orderByDesc('id')
                                ->first();

                            $record->asset->asset_user_id = $previousUsage ? $previousUsage->employee_id : null;
                            $record->asset->save();
                        }
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these usage histories?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Usage histories deleted successfully.')
                        ->requiresConfirmation()
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if ($record->asset && is_null($record->usage_end_date)) {
                                    $record->asset->asset_user_id = null;
                                    $record->asset->save();
                                }
                            }
                        }),
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
            'index' => Pages\ListITAssetUsageHistories::route('/'),
            'create' => Pages\CreateITAssetUsageHistory::route('/create'),
            'edit' => Pages\EditITAssetUsageHistory::route('/{record}/edit'),
        ];
    }
}
