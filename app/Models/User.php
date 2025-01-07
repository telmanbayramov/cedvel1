<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // JWTSubject arayüzü
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject // Arayüzü uygulayın
{
    use HasApiTokens,Notifiable, HasFactory,HasRoles,HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'email',
        'password',
        'duty',
        'employee_type',
        'faculty_id',
        'department_id',
        'status'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * JWTSubject arayüzü için gerekli olan metotlar.
     */

    // Kullanıcının JWT kimliğini döndürür.
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Genellikle 'id' sütunu döner.
    }

    // JWT özel taleplerini döndürür.
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function department()
{
    return $this->belongsTo(Department::class);
}
}
