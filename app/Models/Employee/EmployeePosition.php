<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName(): string
    {
        return 'positionId';
    }

    public function division()
    {
        return $this->belongsTo(EmployeeDivision::class, 'division_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'position_id');
    }
    public function usageHistories()
    {
        return $this->hasMany(\App\Models\IT\ITAssetUsageHistory::class, 'position_id');
    }
}
