<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class ResourcePermission extends Model
{
    protected $fillable = [
        'resource_name',
        'permissionId'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'resource_permission_role');
    }

    /**
     * Get permitted role names for this resource permission.
     */
    public static function getPermittedRolesForModel($modelName): array
    {
        return static::where('resource_name', $modelName)
            ->first()?->roles->pluck('name')->toArray() ?? [];
    }
}
