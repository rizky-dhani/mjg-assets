<?php

namespace App\Filament\Resources\GA\GaAssetCategoryResource\Pages;

use App\Filament\Resources\GA\GaAssetCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGaAssetCategory extends EditRecord
{
    protected static string $resource = GaAssetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}