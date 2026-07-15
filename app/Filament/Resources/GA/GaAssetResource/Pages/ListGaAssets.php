<?php

namespace App\Filament\Resources\GA\GaAssetResource\Pages;

use App\Exports\GA\GaAssetsExport;
use App\Exports\GA\GaAssetsTemplateExport;
use App\Filament\Resources\GA\GaAssetResource;
use App\Imports\GA\GaAssetsImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListGaAssets extends ListRecords
{
    protected static string $resource = GaAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_template')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    try {
                        return Excel::download(
                            new GaAssetsTemplateExport,
                            'ga_assets_import_template.xlsx'
                        );
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Download failed: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\Action::make('export_excel')
                ->label('Export to Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    try {
                        return Excel::download(
                            new GaAssetsExport,
                            'ga_assets_export.xlsx'
                        );
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Export failed: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Excel File')
                        ->disk('local')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = \Storage::path($data['file']);
                        Excel::import(
                            new GaAssetsImport,
                            $filePath
                        );
                        Notification::make()
                            ->title('Import successful!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import failed: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Actions\CreateAction::make()
                ->successNotificationTitle('Asset successfully created'),
        ];
    }
}
