<?php

namespace App\Livewire\Public;

use App\Models\GA\GaAsset;
use App\Models\IT\ITAsset;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DetailAsset extends Component
{
    public $asset;

    public $assetId;

    public function mount($assetId)
    {
        if (auth()->user()->division->initial === 'IT') {
            $this->asset = ITAsset::where('assetId', $assetId)->first();
        } else {
            $this->asset = GaAsset::where('assetId', $assetId)->first();
        }
    }

    #[Title('Detail Asset')]
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.public.detail-asset', [
            'asset' => $this->asset,
        ]);
    }
}
