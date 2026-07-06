<?php

namespace App\Models\GA;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeDepartment;
use App\Models\Employee\EmployeeDivision;
use App\Models\Employee\EmployeePosition;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GaAssetUsageHistory extends Model
{
    protected $guarded = ['id'];

    protected $table = 'ga_asset_usage_histories';

    public function asset()
    {
        return $this->belongsTo(GaAsset::class, 'asset_id');
    }

    public function location()
    {
        return $this->belongsTo(GaAssetLocation::class, 'asset_location_id');
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

    public function room()
    {
        return $this->belongsTo(GaAssetRoom::class, 'room_id');
    }
}
