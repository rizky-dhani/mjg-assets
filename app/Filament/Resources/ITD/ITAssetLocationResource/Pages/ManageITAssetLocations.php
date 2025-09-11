<?php

namespace App\Filament\Resources\ITD\ITAssetLocationResource\Pages;

use App\Filament\Resources\ITD\ITAssetLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageITAssetLocations extends ManageRecords
{
    protected static string $resource = ITAssetLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New IT Asset Location'),
        ];
    }
}
