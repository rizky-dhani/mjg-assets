<?php

namespace App\Filament\Resources\ITD;

use App\Traits\HasResourceRolePermissions;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ITAssetCategory;
use Filament\Resources\Resource;
use App\Models\ResourcePermission;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITD\ITAssetCategoryResource\Pages;
use App\Filament\Resources\ITD\ITAssetCategoryResource\RelationManagers;

class ITAssetCategoryResource extends Resource
{
    use HasResourceRolePermissions;
    protected static ?string $model = ITAssetCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'it-asset-categories';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationParentItem = 'IT Assets';
    protected static ?string $navigationGroup = ' ITD';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $modelLabel = 'IT Asset Category';
    protected static ?string $pluralModelLabel = 'IT Asset Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ITAssetCategory::class, 'name', ignoreRecord: true),
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('remarks')
                    ->label('Remarks')
                    ->nullable()
                    ->maxLength(500)
                    ->autosize()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Categories Found')
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this category?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Category deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these categories?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Categories deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageITAssetCategories::route('/'),
        ];
    }

}
