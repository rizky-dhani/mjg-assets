<?php

namespace App\Filament\Resources\ITD\ITAssetMaintenanceResource\Pages;

use App\Filament\Resources\ITD\ITAssetMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditITAssetMaintenance extends EditRecord
{
    protected static string $resource = ITAssetMaintenanceResource::class;
    protected static ?string $title = 'Edit IT Asset Maintenance Log';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
