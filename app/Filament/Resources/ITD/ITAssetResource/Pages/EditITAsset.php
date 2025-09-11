<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Pages;

use App\Filament\Resources\ITD\ITAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditITAsset extends EditRecord
{
    protected static string $resource = ITAssetResource::class;
    protected static ?string $title = 'Edit IT Asset';
    protected ?bool $hasDatabaseTransactions = true;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record->assetId
    ]);
    }

    public function getRelationManagers(): array
    {
        return []; // Return an empty array to hide relation managers
    }
}
