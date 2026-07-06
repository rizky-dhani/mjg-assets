<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Pages;

use App\Exports\ITD\ITAssetsExport;
use App\Filament\Resources\ITD\ITAssetResource;
use App\Models\IT\ITAssetCategory;
use Filament\Actions;
use Filament\Forms\Select;
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
                ->form([
                    Select::make('category_ids')
                        ->multiple()
                        ->options(fn () => ITAssetCategory::pluck('name', 'id'))
                        ->label('Select Categories')
                        ->placeholder('All categories')
                        ->hint('Leave empty to export all categories'),
                ])
                ->action(function (array $data) {
                    return Excel::download(
                        new ITAssetsExport(categoryIds: $data['category_ids'] ?? []),
                        'it-assets.xlsx'
                    );
                }),
        ];
    }
}
