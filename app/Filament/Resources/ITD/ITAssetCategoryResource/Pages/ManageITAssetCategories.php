<?php

namespace App\Filament\Resources\ITD\ITAssetCategoryResource\Pages;

use App\Filament\Resources\ITD\ITAssetCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageITAssetCategories extends ManageRecords
{
    protected static string $resource = ITAssetCategoryResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New IT Asset Category'),
        ];
    }
}
