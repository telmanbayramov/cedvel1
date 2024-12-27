<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user)
    {
        // Örnek: Sadece adminler tüm kullanıcıları görebilir.
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view a specific user.
     */
    public function view(User $user, User $model)
    {
        // Örnek: Sadece admin veya kendisi görebilir.
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user)
    {
        // Örnek: Sadece admin kullanıcı ekleyebilir.
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update a specific user.
     */
    public function update(User $user, User $model)
    {
        // Örnek: Sadece admin güncelleyebilir veya kullanıcı kendi bilgilerini değiştirebilir.
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete a specific user.
     */
    public function delete(User $user, User $model)
    {
        // Örnek: Sadece admin kullanıcı silebilir.
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore a specific user.
     */
    public function restore(User $user, User $model)
    {
        // Örnek: Sadece admin kullanıcıları geri yükleyebilir.
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete a specific user.
     */
    public function forceDelete(User $user, User $model)
    {
        // Örnek: Sadece admin kullanıcıları kalıcı olarak silebilir.
        return $user->role === 'admin';
    }
}
