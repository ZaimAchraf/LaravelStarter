<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register services here if needed
    }

    public function boot(): void
    {
        // General app boot logic (e.g., observers)
    }
}
