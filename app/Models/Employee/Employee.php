<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = ['id'];
    public function getRouteKeyName(): string
    {
        return 'employeeId';
    }
    public function division()
    {
        return $this->belongsTo(\App\Models\Employee\EmployeeDivision::class);
    }
    public function position()
    {
        return $this->belongsTo(\App\Models\Employee\EmployeePosition::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
