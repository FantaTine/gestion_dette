<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role->name === 'admin';
    }

    public function view(User $user, User $model)
    {
        return $user->role->name === 'admin' || $user->id === $model->id;
    }

    public function create(User $user)
    {
        return in_array($user->role->name, ['admin', 'boutiquier']);
    }

    public function update(User $user, User $model)
    {
        return $user->role->name === 'admin' || $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->role->name === 'admin';
    }
}
