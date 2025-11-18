<?php

namespace App\Policies\IT;

use App\Models\IT\ITAsset;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ITAssetPolicy
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
    public function view(User $user, ITAsset $iTAsset): bool
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
    public function update(User $user, ITAsset $iTAsset): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ITAsset $iTAsset): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ITAsset $iTAsset): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ITAsset $iTAsset): bool
    {
        return $user->division && $user->division->abbreviation === 'ITD';
    }
}
