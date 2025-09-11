<?php

namespace App\Filament\Resources\Employee;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\EmployeeDivision;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasResourceRolePermissions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;
use App\Filament\Resources\Employee\EmployeeDivisionResource\RelationManagers;

class EmployeeDivisionResource extends Resource
{
    use HasResourceRolePermissions;
    protected static ?string $model = EmployeeDivision::class;
    protected static ?string $navigationLabel = 'Divisions';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationParentItem = 'Employees';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Division Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('abbreviation')
                            ->label('Initial')
                            ->required()
                            ->maxLength(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Division Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('abbreviation')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->department ? "{$record->department->name}" : 'N/A')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('addDepartment')
                    ->label('Department')
                    ->icon('heroicon-o-plus')
                    ->color('dark')
                    ->form([
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->department_id = $data['department_id'] ?? null;
                        $record->save();
                    })
                    ->modalHeading('Add Division to Department')
                    ->modalButton('Add to Department')
                    ->modalSubmitAction(fn ($action) => $action->color('primary'))
                    ->successNotificationTitle('Division added to Department successfully'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this division?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Division deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('addToDepartment')
                        ->label('Add To Department...')
                        ->icon('heroicon-o-plus')
                        ->form([
                            Select::make('department_id')
                                ->label('Department')
                                ->relationship('department', 'name')
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                $record->department_id = $data['department_id'] ?? null;
                                $record->save();
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->modalHeading('Add Selected Divisions to Department')
                        ->successNotificationTitle('Divisions added to department successfully.'),
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these divisions?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Divisions deleted successfully.')
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
            'index' => Pages\ListEmployeeDivisions::route('/'),
            'create' => Pages\CreateEmployeeDivision::route('/create'),
            'edit' => Pages\EditEmployeeDivision::route('/{record}/edit'),
        ];
    }
}
