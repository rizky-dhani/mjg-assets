<?php

namespace App\Filament\Resources\GA\GaAssetLocationResource\Pages;

use App\Filament\Resources\GA\GaAssetLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListGaAssetLocations extends ListRecords
{
    protected static string $resource = GaAssetLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Asset Location')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->mutateFormDataUsing(function (array $data): array {
                    // Your custom mutation logic here
                    $data['locationId'] = Str::orderedUuid();

                    return $data;
                })
                ->successNotificationTitle('Asset Location successfully created'),
        ];
    }
}
