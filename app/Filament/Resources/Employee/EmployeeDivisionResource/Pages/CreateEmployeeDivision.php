<?php

namespace App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;

use App\Filament\Resources\Employee\EmployeeDivisionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateEmployeeDivision extends CreateRecord
{
    protected static string $resource = EmployeeDivisionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['divisionId'] = Str::orderedUuid();
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
