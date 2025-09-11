<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ITAssetMaintenance extends Model
{
    protected $guarded = ['id'];
    protected $table = 'it_asset_maintenances';
    protected $casts = [
        'maintenance_date' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(ITAsset::class, 'asset_id');
    }
    public function division()
    {
        return $this->belongsTo(EmployeeDivision::class, 'division_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }
}
