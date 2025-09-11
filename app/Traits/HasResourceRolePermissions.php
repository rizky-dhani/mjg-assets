<?php

namespace App\Traits;

use App\Models\ResourcePermission;

trait HasResourceRolePermissions
{
    public static function getPermittedRoles(): array
    {
        return ResourcePermission::where('resource_name', static::$model)
            ->first()?->roles->pluck('name')->toArray() ?? [];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin'], self::getPermittedRoles());
    }
}
