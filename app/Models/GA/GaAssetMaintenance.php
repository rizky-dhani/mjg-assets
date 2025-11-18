<?php

namespace App\Models\GA;

use Illuminate\Database\Eloquent\Model;

class GaAssetMaintenance extends Model
{
    protected $guarded = ['id'];
    protected $table = 'ga_asset_maintenances';
    protected $casts = [
        'maintenance_date' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(GaAsset::class, 'asset_id');
    }

    public function division()
    {
        return $this->belongsTo(\App\Models\Employee\EmployeeDivision::class, 'division_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'pic_id');
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'employee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'reviewer_id');
    }
}