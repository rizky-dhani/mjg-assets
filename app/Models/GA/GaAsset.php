<?php

namespace App\Models\GA;

use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GaAsset extends Model
{
    protected $guarded = ['id'];

    protected $table = 'ga_assets';

    public function getRouteKeyName(): string
    {
        return 'assetId';
    }

    public function location()
    {
        return $this->belongsTo(GaAssetLocation::class, 'asset_location_id');
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
        return $this->hasMany(GaAssetMaintenance::class, 'asset_id');
    }

    public function category()
    {
        return $this->belongsTo(GaAssetCategory::class, 'asset_category_id');
    }

    public function usageHistory()
    {
        return $this->hasMany(GaAssetUsageHistory::class, 'asset_id');
    }
}
