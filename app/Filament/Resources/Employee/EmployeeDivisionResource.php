<?php

namespace App\Filament\Resources\Employee;

use App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;
use App\Models\Employee\EmployeeDivision;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class EmployeeDivisionResource extends Resource
{
    protected static ?string $model = EmployeeDivision::class;

    protected static ?string $navigationLabel = 'Divisions';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

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
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Division Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('initial')
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
                TextColumn::make('initial')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->department ? "{$record->department->name}" : 'N/A'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\Action::make('addDepartment')
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
                Actions\EditAction::make(),
                Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this division?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Division deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('addToDepartment')
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
                    Actions\DeleteBulkAction::make()
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
        ];
    }
}
