<?php

namespace App\Filament\Resources\GA\GaAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\GA\GaAssetResource;

class ViewGaAsset extends ViewRecord
{
    protected static string $resource = GaAssetResource::class;
    protected static ?string $title = 'View Asset';
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->action(fn ($record) => redirect()->route('filament.admin.resources.it-assets.view', ['record' => $record->assetId]))
                ->color('gray'),
            Actions\Action::make('asset_detail')
                ->label('Detail')
                ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                ->color('warning'),
            Actions\EditAction::make(),
        ];
    }
}
