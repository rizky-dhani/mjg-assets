<?php

namespace App\Filament\Resources\GA\GaAssetRoomResource\Pages;

use App\Filament\Resources\GA\GaAssetRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGaAssetRooms extends ListRecords
{
    protected static string $resource = GaAssetRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle('Room successfully created'),
        ];
    }
}