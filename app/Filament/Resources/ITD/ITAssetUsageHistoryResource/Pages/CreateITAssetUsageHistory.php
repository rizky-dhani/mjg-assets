<?php

namespace App\Filament\Resources\ITD\ITAssetUsageHistoryResource\Pages;

use App\Filament\Resources\ITD\ITAssetUsageHistoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use App\Models\ITAsset;
use Illuminate\Support\Str;

class CreateITAssetUsageHistory extends CreateRecord
{
    protected static string $resource = ITAssetUsageHistoryResource::class;
    protected static ?string $title = 'Create IT Asset Usage History';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['usageId'] = Str::orderedUuid();
        return $data;
    }
    protected function afterCreate(): void
    {
        $itAssetUsageHistory = $this->record;
        $itAsset = ITAsset::where('id', $itAssetUsageHistory->asset_id)->first();
        if($itAsset){
            ITAsset::where('id', $itAssetUsageHistory->asset_id)->update([
                'asset_location_id' => $itAssetUsageHistory->asset_location_id,
                'asset_user_id' => $itAssetUsageHistory->employee_id,
            ]);
            // Find the previous usage history record for this asset, before the newly created one
            $previousUsage = $itAsset->usageHistory()
                ->where('id', '!=', $itAssetUsageHistory->id)
                ->where('usage_start_date', '<', $itAssetUsageHistory->usage_start_date)
                ->whereNull('usage_end_date')
                ->orderByDesc('usage_start_date')
                ->first();

            if ($previousUsage) {
                $previousUsage->update([
                    'asset_location_id' => $itAssetUsageHistory->asset_location_id,
                    'usage_end_date' => $itAssetUsageHistory->usage_start_date,
                ]);
            }
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
