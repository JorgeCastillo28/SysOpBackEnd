<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ResetPassword as ResetPassword;

class User extends Authenticatable
{
    use HasApiTokens;
    use LaratrustUserTrait;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'email_verified_at', 'phone', 'birthdate', 'password', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Se encuentra el usuario logueado por passport por el campo username
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    // Se obtiene el rol de usuario
    public function role()
    {
        return $this->roles->first();
    }

    // Se comprueba si el usuario es administrador
    public function isAdmin()
    {
        $role = Role::where('name', 'admin')->first();
        if ($role):
            return $this->roles->contains($role->id);
        else:
            return false;
        endif;
    }

    // Se comprueba si el usuario es un empleado
    public function isEmployee()
    {
        $role = Role::where('name', 'employee')->first();
        if ($role):
            return $this->roles->contains($role->id);
        else:
            return false;
        endif;
    }
}