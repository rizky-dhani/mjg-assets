<?php

namespace App\Policies\GA;

use App\Models\GA\GaAssetUsageHistory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GaAssetUsageHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GaAssetUsageHistory $gaAssetUsageHistory): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GaAssetUsageHistory $gaAssetUsageHistory): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GaAssetUsageHistory $gaAssetUsageHistory): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GaAssetUsageHistory $gaAssetUsageHistory): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GaAssetUsageHistory $gaAssetUsageHistory): bool
    {
        return $user->division && $user->division->initial === 'GA';
    }
}
