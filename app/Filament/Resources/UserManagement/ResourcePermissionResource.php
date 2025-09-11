<?php

namespace App\Filament\Resources\UserManagement;

use App\Traits\HasResourceRolePermissions;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ResourcePermission;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Resources\UserManagement\ResourcePermissionResource\Pages;
use Spatie\Permission\Models\Role;

class ResourcePermissionResource extends Resource
{
    use HasResourceRolePermissions;
    protected static ?string $model = ResourcePermission::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Resource Permissions';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
                Select::make('resource_name')
                    ->label('Resource Name')
                    ->options(function ($get, $record) {
                        // Get all resource models
                        $allResources = collect(\Filament\Facades\Filament::getResources())
                            ->mapWithKeys(fn($resource) => [
                                $resource::getModel() => class_basename($resource::getModel())
                            ]);

                        // Get already used resource_names, except for the current record (when editing)
                        $usedResourceNames = ResourcePermission::query()
                            ->when($record, fn($query) => $query->where('id', '!=', $record->id))
                            ->pluck('resource_name')
                            ->toArray();

                        // Filter out used resource_names
                        return $allResources->except($usedResourceNames);
                    })
                    ->searchable()
                    ->required(),
                Select::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('resource_name')->label('Model')->sortable()->searchable(),
                TextColumn::make('roles.name')->label('Roles')->sortable()->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this permission?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Permission deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these permissions?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Permissions deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageResourcePermissions::route('/'),
        ];
    }
}
