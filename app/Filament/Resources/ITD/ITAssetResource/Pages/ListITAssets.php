<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Pages;

use App\Exports\ITD\ITAssetsExport;
use App\Filament\Resources\ITD\ITAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListITAssets extends ListRecords
{
    protected static string $resource = ITAssetResource::class;
    protected static ?string $title = 'Assets';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Asset'),
            Actions\Action::make('export_excel')
                ->label('Export to Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(fn () => Excel::download(new ITAssetsExport, 'it-assets.xlsx')),
        ];
    }
}
