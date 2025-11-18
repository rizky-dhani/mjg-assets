<?php

namespace App\Policies\IT;

use App\Models\IT\ITAssetMaintenance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ITAssetMaintenancePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ITAssetMaintenance $iTAssetMaintenance): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ITAssetMaintenance $iTAssetMaintenance): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ITAssetMaintenance $iTAssetMaintenance): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ITAssetMaintenance $iTAssetMaintenance): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ITAssetMaintenance $iTAssetMaintenance): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }
}
