<?php

namespace App\Filament\Resources\ITD;

use Filament\Tables;
use App\Models\ITAsset;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ITAssetCategory;
use App\Models\ITAssetLocation;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\ResourcePermission;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasResourceRolePermissions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ITD\ITAssetResource\Pages;
use App\Filament\Resources\ITD\ITAssetResource\RelationManagers\UsageHistoryRelationManager;

class ITAssetResource extends Resource
{
    use HasResourceRolePermissions;

    protected static ?string $model = ITAsset::class;
    protected static ?string $navigationLabel = 'IT Assets';
    protected static ?string $slug = 'it-assets';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = ' ITD';
    public static function getBreadcrumb(): string
    {
        return 'IT Assets';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('asset_name')
                    ->label('Asset Name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('asset_name', strtoupper($state))),
                Select::make('asset_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->reactive() // This makes the field reactive
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Fetch the remarks from the selected category
                        $category = ITAssetCategory::find($state);
                        $set('asset_remarks', $category?->remarks ?? '');
                    }),     
                DatePicker::make('asset_year_bought')
                    ->label('Asset Year')
                    ->native(false)
                    ->displayFormat('Y')
                    ->format('Y')
                    ->closeOnDateSelection()
                    ->default(now())
                    ->required(),
                Grid::make(3)
                    ->schema([
                        TextInput::make('asset_brand')
                            ->label('Brand')
                            ->afterStateUpdated(fn ($state, callable $set) => $set('asset_brand', strtoupper($state))),
                        TextInput::make('asset_model')
                            ->label('Model')
                            ->maxLength(100)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('asset_model', strtoupper($state))),
                        TextInput::make('asset_serial_number')
                            ->label('Serial Number')
                            ->maxLength(100)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('asset_serial_number', strtoupper($state))),  
                    ]),
                TextInput::make('asset_price')
                    ->label('Price')
                    ->prefix('Rp')
                    ->reactive()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 0, ',', '.') : '')
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            // Remove any existing formatting first
                            $cleanValue = preg_replace('/[^0-9]/', '', $state);
                            // Format the number with thousand separators
                            $formattedValue = number_format((int) $cleanValue, 0, ',', '.');
                            $set('asset_price', $formattedValue);
                        }
                    })
                    ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),
                Select::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'First Hand' => 'First Hand',
                        'Used' => 'Used',
                        'Defect' => 'Defect',
                        'Disposed' => 'Disposed',
                    ])
                    ->required(),
                Textarea::make('asset_notes')
                    ->label('History/Notes')
                    ->maxLength(500)
                    ->columnSpanFull(),
                Textarea::make('asset_remarks')
                    ->label('Remark')
                    ->maxLength(500)
                    ->autosize()
                    ->reactive()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at'))
            ->emptyStateHeading('No IT Assets Found')
            ->columns([
                TextColumn::make('asset_name')
                    ->label('Asset Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_code')
                    ->label('Asset Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_serial_number')
                    ->label('Serial Number')
                    ->getStateUsing(fn ($record) => $record->asset_serial_number ? strtoupper($record->asset_serial_number) : 'N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_year_bought')
                    ->label('Asset Year')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asset_category_id')
                    ->label('Category')
                    ->getStateUsing(fn ($record) => $record->category ? "{$record->category->name}" : 'N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_condition')
                    ->label('Condition')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_user_id')
                    ->label('User')
                    ->getStateUsing(fn ($record) => $record->employee ? "{$record->employee->name}" : 'N/A')
                    ->searchable(query: fn (Builder $query, string $search): Builder => 
                        $query->whereHas('employee', fn (Builder $query) => 
                            $query->where('name', 'like', "%{$search}%")
                        )
                    )
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Position')
                    ->getStateUsing(function ($record) {
                        $latestUsage = $record->usageHistory()->latest('created_at')->first();
                        if ($latestUsage && $latestUsage->usage_end_date) {
                            return 'N/A';
                        }
                        return $latestUsage && $latestUsage->position ? $latestUsage->position->name : 'N/A';
                    })
                    ->sortable()
                    ->searchable(false),
                TextColumn::make('pic_id')
                    ->label('Created By')
                    ->formatStateUsing(function ($record){
                        $initial = $record->user->employee->initial ?? '';
                        $signature = $initial . ' ' . strtoupper($record->created_at->format('d M Y'));
                        return $signature;
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->searchable(),
                SelectFilter::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'First Hand' => 'First Hand',
                        'Used' => 'Used',
                        'Defect' => 'Defect',
                        'Disposed' => 'Disposed',
                    ]),
                Filter::make('asset_user_id')
                    ->form([
                        Checkbox::make('available')
                            ->label('Available'),
                        Checkbox::make('in_use')
                            ->label('In Use'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['available'] ?? false, fn ($query) => $query->whereNull('asset_user_id'))
                            ->when($data['in_use'] ?? false, fn ($query) => $query->whereNotNull('asset_user_id'));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $indicators = [];

                        if ($data['available'] ?? false) {
                            $indicators[] = 'Status: Available';
                        }
                        if ($data['in_use'] ?? false) {
                            $indicators[] = 'Status: In Use';
                        }

                        return empty($indicators) ? null : implode(', ', $indicators);
                    })
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('Detail')
                        ->label('Detail')
                        ->color('warning')
                        ->icon('heroicon-o-information-circle')
                        ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                        ->openUrlInNewTab(),
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()    
                        ->modalHeading('Are you sure you want to delete this asset?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Asset deleted successfully.')
                        ->requiresConfirmation()
                        ->after(function ($record) {
                            // After deleting, delete the asset's usage history
                            if ($record->asset) {
                                $record->asset->usageHistory()->delete();
                            }
                        }),
                ])
                ->icon('heroicon-m-ellipsis-horizontal')
                ->tooltip('Actions')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these assets?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Assets deleted successfully.')
                        ->requiresConfirmation()
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if ($record->asset) {
                                    $record->asset->usageHistory()->delete();
                                }
                            }
                        }),
                    Tables\Actions\BulkAction::make('export_pdf')
                        ->label('Export to PDF')
                        ->icon('heroicon-o-document-arrow-down')->icon('heroicon-o-document-arrow-down')
                        ->action(function ($records) {
                            $ids = $records->pluck('id')->toArray();
                            session(['export_asset_ids' => $ids]);
                            return redirect()->route('assets.bulk-export-pdf.export');
                        })
                        ->deselectRecordsAfterCompletion(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Asset Details')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('asset_name')
                            ->label('Asset Name'),
                        TextEntry::make('asset_code')
                            ->label('Asset Code'),
                        TextEntry::make('asset_serial_number')
                            ->label('Serial Number')
                            ->getStateUsing(function ($record) {
                                return $record->asset_serial_number ? strtoupper($record->asset_serial_number) : 'N/A';
                            }),
                        TextEntry::make('asset_year_bought')
                            ->label('Year Bought'),
                        TextEntry::make('asset_brand')
                            ->label('Brand')
                            ->getStateUsing(function ($record) {
                                return $record->asset_brand ? strtoupper($record->asset_brand) : 'N/A';
                            }),
                        TextEntry::make('asset_model')
                            ->label('Model')
                            ->getStateUsing(function ($record) {
                                return $record->asset_model ? strtoupper($record->asset_model) : 'N/A';
                            }),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('asset_price')
                            ->label('Price')
                            ->formatStateUsing(fn ($state) => $state ? 'Rp. ' . number_format($state, 0, ',', '.') : 'N/A'),
                        TextEntry::make('asset_condition')
                            ->label('Condition'),
                        TextEntry::make('location.name')
                            ->label('Location'),
                        TextEntry::make('employee.name')
                            ->label('Asset User')
                            ->getStateUsing(function ($record) {
                                return $record->employee ? $record->employee->name : 'N/A';
                            }),
                        TextEntry::make('asset_notes')
                            ->label('Notes')
                            ->limit(100),
                        TextEntry::make('asset_remarks')
                            ->label('Remark')
                            ->limit(100),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsageHistoryRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListITAssets::route('/'),
            'create' => Pages\CreateITAsset::route('/create'),
            'edit' => Pages\EditITAsset::route('/{record}/edit'),
            'view' => Pages\ViewITAsset::route('/{record}'),
        ];
    }
}
