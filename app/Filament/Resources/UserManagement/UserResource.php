<?php

namespace App\Filament\Resources\UserManagement;

use App\Filament\Resources\UserManagement\UserResource\Pages;
use App\Models\Employee\EmployeeDivision;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['Super Admin', 'ITD']);
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('User Information')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->preload()
                            ->searchable()
                            ->placeholder('Select Division')
                            ->columnSpanFull(),
                        Select::make('roles')
                            ->label('Roles')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->required()
                            ->searchable()
                            ->placeholder('Select Roles')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->label('Division')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->successNotificationTitle('User updated successfully'),
                Action::make('resetPassword')
                    ->label('Reset Password')
                    ->icon(Heroicon::Key)
                    ->color('warning')
                    ->modalHeading('Reset User Password')
                    ->modalDescription('Enter a new password for this user.')
                    ->schema([
                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)),
                    ])
                    ->action(function (array $data, User $record): void {
                        $record->update(['password' => $data['password']]);

                        Notification::make()
                            ->title('Password reset successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalSubmitActionLabel('Reset Password'),
                DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this user?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('User deleted successfully')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these users?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Selected User(s) deleted successfully')
                        ->requiresConfirmation(),
                    Actions\BulkAction::make('updateDivision')
                        ->label('Update Division')
                        ->icon('heroicon-o-arrows-right-left')
                        ->form([
                            Select::make('division_id')
                                ->label('New Division')
                                ->options(EmployeeDivision::pluck('name', 'id'))
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                                ->preload()
                                ->searchable()
                                ->placeholder('Select Division')
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'division_id' => $data['division_id'],
                                ]);
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading('Update Division for Selected Users')
                        ->modalDescription('Are you sure you want to update the division for the selected users?')
                        ->successNotificationTitle('Division updated successfully for selected users'),
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
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
