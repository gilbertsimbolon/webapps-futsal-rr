<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'owner']);
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $branch->user_id === $user->id
            );
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'owner']);
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $branch->user_id === $user->id
            );
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $branch->user_id === $user->id
            );
    }
}
