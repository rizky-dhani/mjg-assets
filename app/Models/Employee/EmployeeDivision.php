<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeDivision extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName(): string
    {
        return 'divisionId';
    }
    public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }
    public function usageHistories()
    {
        return $this->hasMany(\App\Models\IT\ITAssetUsageHistory::class, 'division_id');
    }
}
