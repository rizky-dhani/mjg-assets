<?php

namespace App\Filament\Resources\UserManagement;

use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\UserManagement\RoleResource\Pages;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static string | \UnitEnum | null $navigationGroup = 'User Management';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-lock-closed';
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('Super Admin');
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['Super Admin']);
    }
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this role?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Role deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these roles?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Roles deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
