<?php

namespace App\Filament\Resources\GA\GaAssetCategoryResource\Pages;

use App\Filament\Resources\GA\GaAssetCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaAssetCategories extends ListRecords
{
    protected static string $resource = GaAssetCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}