<?php

namespace App\Livewire\Public;

use App\Models\IT\ITAsset;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ITAssetDetail extends Component
{
    public $asset;

    public $assetId;

    public function mount($assetId)
    {
        $this->asset = ITAsset::where('assetId', $assetId)->first();
    }

    #[Title('ITD Asset Detail')]
    #[Layout('components.layouts.public')]
    public function render()
    {
        if (! $this->asset) {
            abort(404);
        }

        return view('livewire.public.itd-asset-detail', [
            'asset' => $this->asset,
        ]);
    }
}
