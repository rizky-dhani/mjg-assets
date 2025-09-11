<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Pages;

use Filament\Actions;
use App\Models\ITAsset;
use Illuminate\Database\Eloquent\Model;
use Milon\Barcode\DNS2D;
use Illuminate\Support\Str;
use App\Models\ITAssetCategory;
use App\Models\ITAssetLocation;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ITD\ITAssetResource;
use Illuminate\Support\Facades\DB;

class CreateITAsset extends CreateRecord
{
    protected static string $resource = ITAssetResource::class;
    protected static ?string $title = 'Create IT Asset';
    protected ?bool $hasDatabaseTransactions = true;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['assetId'] = Str::orderedUuid();
        $data['asset_location_id'] = ITAssetLocation::where('name', 'Head Office')->value('id');
        $data['asset_remarks'] = strtoupper($data['asset_remarks'] ?? '');
        $data['pic_id'] = auth()->user()->id;
        $route = route('assets.show', ['assetId' => $data['assetId']]);

        // Generate QR Code
        $qr = new DNS2D();
        $qrCodeImage = base64_decode($qr->getBarcodePNG($route, 'QRCODE,H'));
        $path = 'assets/' . $data['assetId'].'.png';
        $data['barcode'] = $path;
        Storage::disk('public')->put($path, $qrCodeImage);
        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        // Generate asset_code with lockForUpdate
        $categoryCode = ITAssetCategory::where('id', $data['asset_category_id'])->value('code');
        $lastAsset = ITAsset::where('asset_category_id', $data['asset_category_id'])
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();
        $autoIncrement = ITAsset::where('asset_category_id', $data['asset_category_id'])->count() + 1;
        $autoIncrementPadded = str_pad($autoIncrement, 3, '0', STR_PAD_LEFT);
        $data['asset_code'] = 'MJG-INV-ITD.11-' . $categoryCode . '-' . $autoIncrementPadded;

        return static::getModel()::create($data);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
