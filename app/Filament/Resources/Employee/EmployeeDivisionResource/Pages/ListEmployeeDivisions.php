<?php

namespace App\Filament\Resources\Employee\EmployeeDivisionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Employee\EmployeeDivisionResource;

class ListEmployeeDivisions extends ListRecords
{
    protected static string $resource = EmployeeDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Employee Division')
            ->mutateFormDataUsing(function(array $data){
                $data['divisionId'] = Str::orderedUuid();
                return $data;
            }),
        ];
    }
}
