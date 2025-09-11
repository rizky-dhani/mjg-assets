<?php

namespace App\Filament\Resources\Employee\EmployeeDepartmentResource\Pages;

use App\Filament\Resources\Employee\EmployeeDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployeeDepartment extends ManageRecords
{
    protected static string $resource = EmployeeDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Department')
                ->mutateFormDataUsing(function (array $data): array {
                    // Set the initial division to 'Head Office' if not set
                    $data['departmentId'] = \Illuminate\Support\Str::orderedUuid();
                    return $data;
                })
                ->successNotificationTitle('Department created successfully'),
        ];
    }
}
