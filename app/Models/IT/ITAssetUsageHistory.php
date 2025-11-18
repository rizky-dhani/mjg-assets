<?php

namespace App\Models\IT;

use Illuminate\Database\Eloquent\Model;

class ITAssetUsageHistory extends Model
{
    protected $guarded = ['id'];
    protected $table = 'it_asset_usage_histories';

    public function asset()
    {
        return $this->belongsTo(ITAsset::class, 'asset_id');
    }
    public function location()
    {
        return $this->belongsTo(ITAssetLocation::class, 'asset_location_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'asset_user_id');
    }
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'employee_id');
    }
    public function department()
    {
        return $this->belongsTo(\App\Models\Employee\EmployeeDepartment::class, 'department_id');
    }
    public function division()
    {
        return $this->belongsTo(\App\Models\Employee\EmployeeDivision::class, 'division_id');
    }
    public function position()
    {
        return $this->belongsTo(\App\Models\Employee\EmployeePosition::class, 'position_id');
    }
}
