<?php

namespace App\Filament\Resources\Employee\EmployeePositionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Employee\EmployeeResource;
use App\Filament\Resources\Employee\EmployeePositionResource;

class ManageEmployeePositions extends ManageRecords
{
    protected static string $resource = EmployeePositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Employee Position')
                ->modalHeading('Create New Employee Position')
                ->successNotificationTitle('Employee Position created successfully')
                ->mutateFormDataUsing(function (array $data): array {
                    // Set the initial division to 'Head Office' if not set
                    $data['positionId'] = Str::orderedUuid();
                    return $data;
                }),
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Excel File')
                        ->disk('local')
                        // ->acceptedFileTypes(['.xlsx', '.xls'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = \Storage::path($data['file']);
                        \Maatwebsite\Excel\Facades\Excel::import(
                            new \App\Imports\EmployeePositionsImport,
                            $filePath
                        );
                        \Filament\Notifications\Notification::make()
                            ->title('Import successful!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Import failed: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
