<?php

namespace App\Filament\Resources\ITD\ITAssetUsageHistoryResource\Pages;

use App\Filament\Resources\ITD\ITAssetUsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListITAssetUsageHistories extends ListRecords
{
    protected static string $resource = ITAssetUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New IT Asset Usage History'),
        ];
    }
}
