<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Absence;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbsencePolicy
{
    use HandlesAuthorization;
    public function before($user, $ability)
    {
        if ($user->role === 'super_admin') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return in_array($user->role, ['super_admin', 'hr', 'manager']);
    }

    public function view(User $user, Absence $absence)
    {
        return $user->role === 'super_admin' || 
               $user->role === 'hr' || 
               $user->id === $absence->user_id;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['super_admin', 'hr', 'employee']);
    }

    public function update(User $user, Absence $absence)
    {
        return $user->role === 'super_admin' || 
               $user->role === 'hr' || 
               $user->id === $absence->user_id;
    }

    public function delete(User $user, Absence $absence)
    {
        return $user->role === 'super_admin' || $user->role === 'hr';
    }
}