<?php

namespace App\Filament\Resources\GA\GaAssetUsageHistoryResource\Pages;

use App\Filament\Resources\GA\GaAssetUsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGaAssetUsageHistory extends EditRecord
{
    protected static string $resource = GaAssetUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}