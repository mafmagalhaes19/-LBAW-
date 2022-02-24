<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return Auth::check();
    }
    
    public function show(User $user1, User $user2)
    {
        return $user1->id == $user2->id || $user->admin;
    }

    public function list(User $user)
    {
        return Auth::check();
    }


    public function update(User $user1, User $user2)
    {
        return $user1->id == $user2->id || $user1->admin;
    }
}