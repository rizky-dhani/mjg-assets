<?php

use App\Livewire\Public\GaAssetDetail;
use App\Livewire\Public\ITAssetDetail;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

// For IT assets
Route::get('itd/public/asset-detail/{assetId}', ITAssetDetail::class)
    ->name('itd.assets.show');
// For GA assets
Route::get('general-affairs/public/asset-detail/{assetId}', GaAssetDetail::class)
    ->name('general-affairs.assets.show');

Route::get('/assets/bulk-export-pdf/export', function () {
    $ids = session()->get('export_asset_ids', []);

    // Determine the correct asset model based on the user's department/division
    $user = auth()->user();
    $assets = null;

    // Check if user has a division or department that indicates GA vs IT
    if ($user && $user->division->initial === 'GA') {
        $assets = \App\Models\GA\GaAsset::whereIn('id', $ids)->get();
        $filename = 'GA-ASSETS-'.now()->format('Y-m-d').'.pdf';
    } else {
        // Default to IT assets or if user is from IT department
        $assets = \App\Models\IT\ITAsset::whereIn('id', $ids)->get();
        $filename = 'IT-ASSETS-'.now()->format('Y-m-d').'.pdf';
    }

    $html = view('pdf.assets-list', compact('assets'));
    $pdf = Browsershot::html($html)
        ->format('A4')
        ->showBackground()
        ->pdf();

    // return $html;
    return response($pdf, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="'.$filename.'"');

})->name('assets.bulk-export-pdf.export');
