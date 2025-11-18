<?php

namespace App\Traits;

trait HasResourceRolePermissions
{
    public static function canViewAny(): bool
    {
        // By default, only Super Admin can access resources that use this trait
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }
}
