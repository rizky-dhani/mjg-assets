<?php

namespace App\Filament\Resources\ITD\ITAssetUsageHistoryResource\Pages;

use App\Filament\Resources\ITD\ITAssetUsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditITAssetUsageHistory extends EditRecord
{
    protected static string $resource = ITAssetUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        if ($this->record->asset) {
            $this->record->asset->asset_user_id = $this->record->employee_id;
            $this->record->asset->asset_location_id = $this->record->asset_location_id;
            $this->record->asset->save();
        }
    }
    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.it-assets.view', [
            'record' => $this->record->asset->assetId,
        ]);
    }
}
