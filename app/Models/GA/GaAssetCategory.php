<?php

namespace App\Models\GA;

use Illuminate\Database\Eloquent\Model;

class GaAssetCategory extends Model
{
    protected $guarded = ['id'];
    protected $table = 'ga_asset_categories';

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function assets()
    {
        return $this->hasMany(GaAsset::class, 'asset_category_id', 'id');
    }
}