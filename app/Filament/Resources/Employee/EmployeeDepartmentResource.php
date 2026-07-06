<?php

namespace App\Filament\Resources\Employee;

use App\Filament\Resources\Employee\EmployeeDepartmentResource\Pages;
use App\Filament\Resources\Employee\EmployeeDepartmentResource\RelationManagers;
use App\Models\Employee\EmployeeDepartment;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeDepartmentResource extends Resource
{
    protected static ?string $model = EmployeeDepartment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Departments';

    protected static ?string $navigationParentItem = 'Employees';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Department Name')
                    ->required()
                    ->unique(EmployeeDepartment::class, 'name', ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Department Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this department?')
                    ->modalButton('Delete Department')
                    ->successNotificationTitle('Department deleted successfully.'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these departments?')
                        ->successNotificationTitle('Departments deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Department Name')
                            ->size(TextEntrySize::Large)
                            ->weight(FontWeight::Bold),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DivisionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployeeDepartment::route('/'),
            'view' => Pages\ViewEmployeeDepartment::route('/{record}'),
        ];
    }
}
