<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    private function registerGates(): void
    {
        Gate::define('access-dashboard', function (User $user) {
            return $user->hasPermission('access-dashboard');
        });

        Gate::define('manage-users', function (User $user) {
            return $user->hasPermission('manage-users');
        });

        Gate::define('manage-clients', function (User $user) {
            return $user->hasPermission('manage-clients');
        });

        Gate::define('manage-all', function (User $user) {
            return $user->hasRole(UserRole::SUPER_ADMIN);
        });
    }
}
