<?php

namespace App\Filament\Resources\GA\GaAssetLocationResource\Pages;

use App\Filament\Resources\GA\GaAssetLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGaAssetLocation extends EditRecord
{
    protected static string $resource = GaAssetLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}