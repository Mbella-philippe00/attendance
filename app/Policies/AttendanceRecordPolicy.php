<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Exécute avant toutes les autres vérifications d'autorisation.
     */
    public function before($user, $ability)
    {
        if ($user->role === 'super_admin') {
            return true;
        }
    }

    /**
     * Détermine si l'utilisateur peut voir tous les enregistrements de présence.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'hr', 'manager']);
    }

    /**
     * Détermine si l'utilisateur peut voir un enregistrement de présence spécifique.
     */
    public function view(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->role === 'hr' || 
               $user->id === $attendanceRecord->user_id ||
               ($user->role === 'manager' && $user->id === $attendanceRecord->user->manager_id);
    }

    /**
     * Détermine si l'utilisateur peut créer un enregistrement de présence.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'hr', 'employee']);
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un enregistrement de présence.
     */
    public function update(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->role === 'hr' || 
               $user->id === $attendanceRecord->user_id ||
               ($user->role === 'manager' && $user->id === $attendanceRecord->user->manager_id);
    }

    /**
     * Détermine si l'utilisateur peut supprimer un enregistrement de présence.
     */
    public function delete(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->role === 'hr';
    }

    /**
     * Détermine si l'utilisateur peut restaurer un enregistrement de présence supprimé.
     */
    public function restore(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->role === 'hr';
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement un enregistrement de présence.
     */
    public function forceDelete(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->role === 'hr';
    }
}
