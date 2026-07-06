<?php

namespace App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;

use App\Filament\Resources\Employee\EmployeeDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListEmployeeDivisions extends ListRecords
{
    protected static string $resource = EmployeeDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Employee Division')
                ->mutateFormDataUsing(function (array $data) {
                    $data['divisionId'] = Str::orderedUuid();

                    return $data;
                }),
        ];
    }
}
