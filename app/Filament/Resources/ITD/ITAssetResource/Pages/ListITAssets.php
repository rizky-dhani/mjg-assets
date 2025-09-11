<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Pages;

use App\Filament\Resources\ITD\ITAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListITAssets extends ListRecords
{
    protected static string $resource = ITAssetResource::class;
    protected static ?string $title = 'IT Assets';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New IT Asset'),
        ];
    }
}
