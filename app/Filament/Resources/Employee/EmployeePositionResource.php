<?php

namespace App\Filament\Resources\Employee;

use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Employee\EmployeePosition;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Employee\EmployeePositionResource\Pages;
use App\Filament\Resources\Employee\EmployeePositionResource\RelationManagers;

class EmployeePositionResource extends Resource
{
    protected static ?string $model = EmployeePosition::class;
    protected static ?string $navigationLabel = 'Positions';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationParentItem = 'Employees';
    protected static string | \UnitEnum | null $navigationGroup = 'User Management';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Position Name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Position Name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this position?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Position deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these positions?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Positions deleted successfully.')
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
            'index' => Pages\ManageEmployeePositions::route('/'),
        ];
    }
}
