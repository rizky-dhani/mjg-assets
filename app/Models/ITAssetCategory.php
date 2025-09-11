<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITAssetCategory extends Model
{
    protected $guarded = ['id'];
    protected $table = 'it_asset_categories';
    public function getRouteKeyName()
    {
        return 'code';
    }
    public function assets()
    {
        return $this->hasMany(ITAsset::class, 'asset_category_id', 'id');
    }
}
