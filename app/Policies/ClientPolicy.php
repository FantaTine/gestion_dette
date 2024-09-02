<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role->name, ['admin', 'boutiquier']);
    }

    public function view(User $user, Client $client)
    {
        return in_array($user->role->name, ['admin', 'boutiquier']) || $user->id === $client->user_id;
    }

    public function create(User $user)
    {
        return in_array($user->role->name, ['admin', 'boutiquier']);
    }

    public function update(User $user, Client $client)
    {
        return in_array($user->role->name, ['admin', 'boutiquier']);
    }

    public function delete(User $user, Client $client)
    {
        return $user->role->name === 'admin';
    }
}
