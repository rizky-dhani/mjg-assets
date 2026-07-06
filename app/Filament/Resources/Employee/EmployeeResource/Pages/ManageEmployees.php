<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use App\Filament\Resources\Employee\EmployeeResource;
use App\Imports\EmployeesImport;
use Filament\Actions;
use Filament\Forms\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ManageEmployees extends ManageRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Employee')
                ->modalHeading('Create New Employee')
                ->successNotificationTitle('Employee created successfully')
                ->mutateFormDataUsing(function (array $data): array {
                    // Set the initial division to 'Head Office' if not set
                    $data['employeeId'] = Str::orderedUuid();

                    return $data;
                }),
            Actions\Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Excel File')
                        ->disk('local')
                        // ->acceptedFileTypes(['.xlsx', '.xls'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = \Storage::path($data['file']);
                        Excel::import(
                            new EmployeesImport,
                            $filePath
                        );
                        Notification::make()
                            ->title('Import successful!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import failed: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
