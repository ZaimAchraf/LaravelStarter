<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'picture',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'role_id' => 'int',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, UserRole|int $role)
    {
        $roleId = is_int($role) ? $role : $role->value;
        return $query->where('role_id', $roleId);
    }

    // Helpers
    public function hasRole(UserRole|int $role): bool
    {
        $roleId = is_int($role) ? $role : $role->value;
        return $this->role_id === $roleId;
    }

    public function hasAnyRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $userRole = UserRole::tryFrom($this->role_id);
        return in_array($permission, $userRole?->permissions() ?? []);
    }

    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->picture) {
            return $this->defaultProfilePhotoUrl();
        }

        $relativePath = 'uploads/users/' . $this->picture;

        if (!file_exists(public_path($relativePath))) {
            return $this->defaultProfilePhotoUrl();
        }

        return asset($relativePath);
    }
}
