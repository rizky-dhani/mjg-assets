<?php

namespace App\Filament\Resources\Employee;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\EmployeeDivision;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\EmployeeDepartment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasResourceRolePermissions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use App\Filament\Resources\Employee\EmployeeDepartmentResource\Pages;
use App\Filament\Resources\Employee\EmployeeDepartmentResource\RelationManagers;

class EmployeeDepartmentResource extends Resource
{
    use HasResourceRolePermissions;
    protected static ?string $model = EmployeeDepartment::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Departments';
    protected static ?string $navigationParentItem = 'Employees';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this department?')
                    ->modalButton('Delete Department')
                    ->successNotificationTitle('Department deleted successfully.')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these departments?')
                        ->successNotificationTitle('Departments deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
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
