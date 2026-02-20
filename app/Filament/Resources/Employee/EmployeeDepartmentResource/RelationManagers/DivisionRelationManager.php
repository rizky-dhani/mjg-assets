<?php

namespace App\Filament\Resources\Employee\EmployeeDepartmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DivisionRelationManager extends RelationManager
{
    protected static string $relationship = 'division';

    public function isReadOnly(): bool
    {
        return false;
    }

    protected static bool $isLazy = true;

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Division Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('initial')
                    ->label('Initial')
                    ->required()
                    ->maxLength(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('initial')
                    ->label('Initial'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\Action::make('assignDivision')
                    ->label('Assign Division')
                    ->icon('heroicon-o-plus')
                    ->action(function (array $data, $livewire) {
                        $selectedDivisions = $data['divisions'] ?? [];
                        $departmentId = $livewire->getOwnerRecord()->id;

                        if (! empty($selectedDivisions)) {
                            // Update each selected division's department_id
                            foreach ($selectedDivisions as $divisionId) {
                                \App\Models\Employee\EmployeeDivision::where('id', $divisionId)
                                    ->update(['department_id' => $departmentId]);
                            }
                        }
                    })
                    ->form([
                        Forms\Components\Select::make('divisions')
                            ->label('Select Divisions')
                            ->multiple()
                            ->options(
                                \App\Models\Employee\EmployeeDivision::query()
                                    ->pluck('name', 'id')
                            )
                            ->required(),
                    ])
                    ->modalHeading('Assign Division')
                    ->modalButton('Assign')
                    ->successNotificationTitle('Division(s) assigned successfully.'),
                Actions\CreateAction::make()
                    ->modalHeading('Create New Division')
                    ->successNotificationTitle('Division created successfully.'),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Division')
                    ->successNotificationTitle('Division updated successfully.'),
                Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this division?')
                    ->modalButton('Delete Division')
                    ->successNotificationTitle('Division deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these divisions?')
                        ->modalButton('Delete Divisions')
                        ->successNotificationTitle('Divisions deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
