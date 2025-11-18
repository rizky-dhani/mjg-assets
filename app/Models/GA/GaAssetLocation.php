<?php

namespace App\Models\GA;

use Illuminate\Database\Eloquent\Model;

class GaAssetLocation extends Model
{
    protected $guarded = ['id'];
    protected $table = 'ga_asset_locations';

    public function assets()
    {
        return $this->hasMany(GaAsset::class, 'asset_location_id');
    }
}