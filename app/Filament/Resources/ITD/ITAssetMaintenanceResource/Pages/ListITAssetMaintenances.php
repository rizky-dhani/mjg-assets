<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenanceResource\Pages;

use App\Filament\Resources\ITD\ITAssetMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListITAssetMaintenances extends ListRecords
{
    protected static string $resource = ITAssetMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Maintenance Log')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['maintenanceId'] = Str::orderedUuid();
                    $data['pic_id'] = auth()->user()->id;

                    return $data;
                })
                ->successNotificationTitle('Maintenance log created successfully'),
        ];
    }
}
