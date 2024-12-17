<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // Diğer işlemler...

    public function delete(User $authUser, User $user)
    {
        // Super admin engellemesi
        if ($authUser->hasRole('super-admin')) {
            dd('Super-admin kullanıcılar diğerlerini silemez.');
            return Response::deny('Super-admin kullanıcılar diğerlerini silemez.');
        }
    
        // Admin engellemesi
        if ($authUser->hasRole('admin')) {
            return $authUser->id === $user->id 
                ? Response::allow() 
                : Response::deny('Yalnızca admin kullanıcıları diğerlerini silebilir.');
        }
    
        // Diğer kullanıcılar için engelleme
        return Response::deny('Bu işlem için yetkiniz yok.');
    }
    
    
    
    
}
