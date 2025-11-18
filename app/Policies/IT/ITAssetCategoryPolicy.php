<?php

namespace App\Policies\IT;

use App\Models\IT\ITAssetCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ITAssetCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ITAssetCategory $iTAssetCategory): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ITAssetCategory $iTAssetCategory): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ITAssetCategory $iTAssetCategory): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ITAssetCategory $iTAssetCategory): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ITAssetCategory $iTAssetCategory): bool
    {
        return $user->division && $user->division->initial === 'ITD';
    }
}
