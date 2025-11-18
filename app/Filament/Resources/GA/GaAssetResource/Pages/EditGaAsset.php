<?php

namespace App\Filament\Resources\GA\GaAssetResource\Pages;

use App\Filament\Resources\GA\GaAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGaAsset extends EditRecord
{
    protected static string $resource = GaAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}