<?php

namespace App\Filament\Resources\GA\GaAssetMaintenanceResource\Pages;

use App\Filament\Resources\GA\GaAssetMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaAssetMaintenances extends ListRecords
{
    protected static string $resource = GaAssetMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}