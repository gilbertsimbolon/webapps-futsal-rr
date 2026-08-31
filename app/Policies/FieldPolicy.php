<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\Field;
use App\Models\User;

class FieldPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'owner']);
    }

    public function view(User $user, Field $field): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $field->branch->user_id === $user->id
            );
    }

    public function createForBranch(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $branch->user_id === $user->id
            );
    }

    public function update(User $user, Field $field): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $field->branch->user_id === $user->id
            );
    }

    public function delete(User $user, Field $field): bool
    {
        return $user->hasRole('admin')
            || (
                $user->hasRole('owner')
                && $field->branch->user_id === $user->id
            );
    }
}
