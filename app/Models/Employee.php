<?php

namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{

    protected $fillable = [
        'fonction',
        'salaire',
        'statut',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
