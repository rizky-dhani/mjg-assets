<?php

namespace App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;

use App\Filament\Resources\Employee\EmployeeDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeDivision extends EditRecord
{
    protected static string $resource = EmployeeDivisionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
