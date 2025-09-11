<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITAsset extends Model
{
    protected $guarded = ['id'];
    protected $table = 'it_assets';
    public function getRouteKeyName(): string
    {
        return 'assetId';
    }
    public function location()
    {
        return $this->belongsTo(ITAssetLocation::class, 'asset_location_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'asset_user_id');
    }
    public function maintenance()
    {
        return $this->hasMany(ITAssetMaintenance::class, 'asset_id');
    }
    public function category()
    {
        return $this->belongsTo(ITAssetCategory::class, 'asset_category_id');
    }
    public function usageHistory()
    {
        return $this->hasMany(ITAssetUsageHistory::class, 'asset_id');
    }
}
