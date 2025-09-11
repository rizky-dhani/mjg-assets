<?php

namespace App\Filament\Resources\Employee\EmployeePositionResource\Pages;

use App\Filament\Resources\Employee\EmployeePositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeePositions extends ListRecords
{
    protected static string $resource = EmployeePositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Employee Position'),
        ];
    }
}
