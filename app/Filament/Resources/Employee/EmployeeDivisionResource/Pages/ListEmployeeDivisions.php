<?php

namespace App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;

use App\Filament\Resources\Employee\EmployeeDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeDivisions extends ListRecords
{
    protected static string $resource = EmployeeDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Employee Division'),
        ];
    }
}
