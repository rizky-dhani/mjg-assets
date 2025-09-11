<?php

namespace App\Filament\Resources\Employee\EmployeeDepartmentResource\Pages;

use App\Filament\Resources\Employee\EmployeeDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployeeDepartment extends ViewRecord
{
    protected static string $resource = EmployeeDepartmentResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Department')
                ->successNotificationTitle('Department updated successfully.'),
            Actions\DeleteAction::make()
                ->modalHeading('Are you sure you want to delete this department?')
                ->modalButton('Delete Department')
                ->successNotificationTitle('Department deleted successfully.'),
        ];
    }
}
