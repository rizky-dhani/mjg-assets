<?php

namespace App\Filament\Resources\SystemManagement;

use App\Filament\Resources\SystemManagement\DatabaseBackupResource\Pages;
use App\Models\DatabaseBackup;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class DatabaseBackupResource extends Resource
{
    protected static ?string $model = DatabaseBackup::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string|\UnitEnum|null $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Database Backup';

    protected static ?string $modelLabel = 'Database Backup';

    protected static ?string $pluralModelLabel = 'Database Backups';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('Super Admin');
    }

    public static function form(Schema $form): Schema
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('filename')
                    ->label('Filename')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state): string => $state ? DatabaseBackup::make(['size' => $state])->size_formatted : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('started_at')
                    ->label('Started At')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (DatabaseBackup $record): bool => $record->status === 'completed')
                    ->action(function (DatabaseBackup $record) {
                        $disk = \Storage::disk($record->disk);
                        if ($disk->exists($record->path)) {
                            return response()->streamDownload(function () use ($disk, $record) {
                                echo $disk->get($record->path);
                            }, $record->filename, [
                                'Content-Type' => 'application/gzip',
                            ]);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Backup file not found on disk')
                            ->danger()
                            ->send();
                    }),

                DeleteAction::make()
                    ->modalHeading('Delete Backup')
                    ->modalDescription('This will permanently delete the backup file and its record.')
                    ->successNotificationTitle('Backup deleted successfully.')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Backups')
                        ->modalDescription('This will permanently delete the selected backup files and their records.')
                        ->successNotificationTitle('Selected backups deleted successfully.')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabaseBackups::route('/'),
        ];
    }
}
