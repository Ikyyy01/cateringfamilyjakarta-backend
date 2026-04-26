<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        ActivityLog::log(
            action:      'created',
            model:       $user,
            description: "User baru \"{$user->name}\" ({$user->email}) terdaftar dengan role [{$user->role}].",
            newValues:   $user->only(['name', 'email', 'role']),
        );
    }

    public function updated(User $user): void
    {
        $dirty = $user->getDirty();
        // Jangan log kalau yang berubah hanya password atau remember_token
        $skip  = ['password', 'remember_token', 'updated_at'];
        $dirty = array_diff_key($dirty, array_flip($skip));
        if (empty($dirty)) return;

        $old = array_intersect_key($user->getOriginal(), $dirty);

        ActivityLog::log(
            action:      'updated',
            model:       $user,
            description: "Profil user \"{$user->name}\" diperbarui.",
            oldValues:   $old,
            newValues:   $dirty,
        );
    }

    public function deleted(User $user): void
    {
        ActivityLog::log(
            action:      'deleted',
            model:       $user,
            description: "User \"{$user->name}\" ({$user->email}) dihapus.",
        );
    }
}
