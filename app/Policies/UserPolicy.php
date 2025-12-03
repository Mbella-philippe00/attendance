<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admins can view any user
        if ($user->isAdmin()) {
            return true;
        }
        
        // Managers can view users in their department
        if ($user->isManager() && $model->department_id === $user->department_id) {
            return true;
        }
        
        // Users can view their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins and managers can create users
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admins can update any user
        if ($user->isAdmin()) {
            return true;
        }
        
        // Managers can update users in their department (but not change roles)
        if ($user->isManager() && $model->department_id === $user->department_id) {
            return true;
        }
        
        // Users can update their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Prevent users from deleting themselves
        if ($user->id === $model->id) {
            return false;
        }
        
        // Admins can delete any user
        if ($user->isAdmin()) {
            return true;
        }
        
        // Managers can delete users in their department (except other admins)
        return $user->isManager() && 
               $model->department_id === $user->department_id && 
               !$model->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admins can force delete, and not themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
