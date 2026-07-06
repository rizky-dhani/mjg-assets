<?php

namespace App\Models\IT;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeDepartment;
use App\Models\Employee\EmployeeDivision;
use App\Models\Employee\EmployeePosition;
use App\Models\User;
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
        return $this->belongsTo(User::class, 'asset_user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }

    public function division()
    {
        return $this->belongsTo(EmployeeDivision::class, 'division_id');
    }

    public function position()
    {
        return $this->belongsTo(EmployeePosition::class, 'position_id');
    }
}
