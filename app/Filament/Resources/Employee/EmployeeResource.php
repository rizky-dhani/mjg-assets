<?php

namespace App\Filament\Resources\Employee;

use App\Traits\HasResourceRolePermissions;
use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Employee\EmployeeResource\Pages;
use App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    use HasResourceRolePermissions;
    protected static ?string $model = Employee::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'User Management';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Select User (Optional, select if employee will have login access)')
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select User'),
                    ]),
                Section::make('Employee Details')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Employee Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('initial')
                            ->label('Initial')
                            ->required()
                            ->unique(Employee::class, 'initial', ignoreRecord: true)
                            ->maxLength(3),
                        TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->required()
                            ->maxLength(10)
                            ->unique(Employee::class, 'employee_number', ignoreRecord: true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->orderByDesc('employee_number');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Employee Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_number')
                    ->label('Employee Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Login Access?')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->user ? 'Yes' : 'No';
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this employee?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Employee deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these employees?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Employees deleted successfully.')
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
            'index' => Pages\ManageEmployees::route('/'),
        ];
    }
}
