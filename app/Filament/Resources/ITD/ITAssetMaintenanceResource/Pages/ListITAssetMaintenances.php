<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenanceResource\Pages;

use App\Filament\Resources\ITD\ITAssetMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListITAssetMaintenances extends ListRecords
{
    protected static string $resource = ITAssetMaintenanceResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New IT Maintenance Log'),
        ];
    }
}
