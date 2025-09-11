<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenanceResource\Pages;

use App\Filament\Resources\ITD\ITAssetMaintenanceResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use App\Models\Employee;
use Illuminate\Support\Str;

class CreateITAssetMaintenance extends CreateRecord
{
    protected static string $resource = ITAssetMaintenanceResource::class;
    protected static ?string $title = 'Create IT Asset Maintenance Log';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['maintenanceId'] = Str::orderedUuid();
        $data['pic_id'] = auth()->user()->id;
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
