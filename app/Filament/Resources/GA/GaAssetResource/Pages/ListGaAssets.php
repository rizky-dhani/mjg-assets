<?php

namespace App\Filament\Resources\GA\GaAssetResource\Pages;

use App\Filament\Resources\GA\GaAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaAssets extends ListRecords
{
    protected static string $resource = GaAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}