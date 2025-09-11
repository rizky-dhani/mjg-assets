<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITAssetLocation extends Model
{
    protected $guarded = ['id'];
    protected $table = 'it_asset_locations';
    public function assets()
    {
        return $this->hasMany(ITAsset::class, 'asset_location_id');
    }
}
