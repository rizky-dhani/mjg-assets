<?php

namespace App\Livewire\Public;

use App\Models\GA\GaAsset;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class GaAssetDetail extends Component
{
    public $asset;

    public $assetId;

    public function mount($assetId)
    {
        $this->asset = GaAsset::where('assetId', $assetId)->first();
    }

    #[Title('GA Asset Detail')]
    #[Layout('components.layouts.public')]
    public function render()
    {
        if (! $this->asset) {
            abort(404);
        }

        return view('livewire.public.general-affairs-asset-detail', [
            'asset' => $this->asset,
        ]);
    }
}
