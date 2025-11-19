<?php

namespace App\Filament\Resources\GA\GaAssetUsageHistoryResource\Pages;

use App\Filament\Resources\GA\GaAssetUsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaAssetUsageHistories extends ListRecords
{
    protected static string $resource = GaAssetUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle('Asset Usage History successfully created'),
        ];
    }
}
