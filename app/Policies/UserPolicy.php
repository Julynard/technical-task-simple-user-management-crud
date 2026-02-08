<?php

namespace App\Policies;

use App\Models\BackendUser;
use App\Models\User;

class UserPolicy
{
    public function viewAny(BackendUser $user): bool
    {
        return $user->can('users.view');
    }

    public function view(BackendUser $user, User $model): bool
    {
        return $user->can('users.view');
    }

    public function create(BackendUser $user): bool
    {
        return $user->can('users.create');
    }

    public function update(BackendUser $user, User $model): bool
    {
        return $user->can('users.update');
    }

    public function delete(BackendUser $user, User $model): bool
    {
        return $user->can('users.delete');
    }
}
