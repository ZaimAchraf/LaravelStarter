<?php

namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;
    use Messagable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'sexe',
        'phone',
        'adresse',
        'is_active',
        'picture',
        'remember_token',
        'role_id'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
