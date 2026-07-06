<?php

namespace App\Models\Employee;

use App\Models\User;
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
        return $this->belongsTo(EmployeeDivision::class);
    }

    public function position()
    {
        return $this->belongsTo(EmployeePosition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
