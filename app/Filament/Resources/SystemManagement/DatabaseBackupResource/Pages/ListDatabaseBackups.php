<?php

namespace App\Filament\Resources\SystemManagement\DatabaseBackupResource\Pages;

use App\Filament\Resources\SystemManagement\DatabaseBackupResource;
use App\Models\DatabaseBackup;
use App\Services\DatabaseBackupService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseBackups extends ListRecords
{
    protected static string $resource = DatabaseBackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create_backup')
                ->label('Create Backup')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Create Database Backup')
                ->modalDescription('This will create a full backup of the database. The process may take some time depending on database size.')
                ->modalSubmitActionLabel('Start Backup')
                ->action(function () {
                    $user = auth()->user();

                    $filename = 'backup_'.config('database.connections.'.config('database.default').'.database').'_'.now()->format('Y-m-d_His').'.sql.gz';

                    $backup = DatabaseBackup::create([
                        'filename' => $filename,
                        'disk' => 'backups',
                        'path' => 'database_backups/'.$filename,
                        'status' => 'pending',
                        'started_at' => now(),
                        'created_by' => $user->id,
                    ]);

                    $service = app(DatabaseBackupService::class);
                    $success = $service->createBackup($backup);

                    if ($success) {
                        Notification::make()
                            ->title('Database backup created successfully')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Database backup failed: '.$backup->error_message)
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
