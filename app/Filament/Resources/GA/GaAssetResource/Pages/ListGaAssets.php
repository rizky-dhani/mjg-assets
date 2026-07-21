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
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('selected_sheet')
                        ->label('Sheet Name (leave empty for first sheet)')
                        ->placeholder('e.g. Sheet1'),
                    \Filament\Forms\Components\TextInput::make('heading_row')
                        ->label('Header Row Number')
                        ->numeric()
                        ->default(8)
                        ->minValue(1)
                        ->required(),
                    \Filament\Forms\Components\Toggle::make('create_usage_history')
                        ->label('Auto Create Asset Usage History')
                        ->default(false)
                        ->reactive(),
                    \Filament\Forms\Components\Select::make('usage_location_id')
                        ->label('Asset Location')
                        ->options(\App\Models\GA\GaAssetLocation::pluck('name', 'id'))
                        ->searchable()
                        ->visible(fn ($get) => $get('create_usage_history'))
                        ->required(fn ($get) => $get('create_usage_history')),
                ])
                ->action(function (array $data) {
                    try {
                        $filePath = \Storage::path($data['file']);
                        $sheetName = !empty($data['selected_sheet']) ? $data['selected_sheet'] : null;
                        $import = new GaAssetsImport;
                        $import->headingRowNumber = (int) ($data['heading_row'] ?? 8);
                        $import->createUsageHistory = (bool) ($data['create_usage_history'] ?? false);
                        $import->usageLocationId = $data['usage_location_id'] ?? null;
                        Excel::import($import, $filePath, null, null, $sheetName);
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
