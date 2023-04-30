<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModeratorPolicy
{
    use HandlesAuthorization;

    public function isAllowed(User $user){
        if($user->role() === 'admin' || $user->role() === 'moderator'){
            return true;
        }else{
            return false;
        }
    }
}
