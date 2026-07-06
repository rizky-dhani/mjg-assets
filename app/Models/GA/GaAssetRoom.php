<?php

namespace App\Models\GA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaAssetRoom extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'ga_asset_rooms';

    public function location()
    {
        return $this->belongsTo(GaAssetLocation::class, 'location_id');
    }

    public function usageHistories()
    {
        return $this->hasMany(GaAssetUsageHistory::class, 'room_id');
    }
}
