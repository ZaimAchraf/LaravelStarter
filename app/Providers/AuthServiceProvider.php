<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('access-dashboard', function (User $user) {
            return in_array($user->role_id, [1, 2]);
        });

        Gate::define('access-forum', function (User $user) {
            return $user->role_id == 3;
        });

        Gate::define('manage-clients', function (User $user) {
            return in_array($user->role_id, [1, 2]);
        });

        Gate::define('manage-admins', function (User $user) {
            return $user->role_id == 1;
        });

        Gate::define('see-schedules', function (User $user) {
            return $user->role_id == 3;
        });

        Gate::define('manage-courses', function (User $user) {
            return $user->role_id == 2;
        });

        Gate::define('see-payments', function (User $user) {
            return $user->role_id == 3;
        });

        Gate::define('manage-payments', function (User $user) {
            return $user->role_id == 2;
        });

        Gate::define('see-offers', function (User $user) {
            return $user->role_id == 3;
        });

        Gate::define('manage-offers', function (User $user) {
            return $user->role_id == 2;
        });

        Gate::define('see-events', function (User $user) {
            return $user->role_id == 3;
        });

        Gate::define('manage-events', function (User $user) {
            return $user->role_id == 2;
        });

        Gate::define('manage-ecommerce', function (User $user) {
            return $user->role_id == 2;
        });
    }
}
