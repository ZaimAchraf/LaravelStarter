<?php

namespace App\Providers;

use App\helper\NumberToWords;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('NumberToWords', function () {
            return new NumberToWords();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        Payment::observe(PaymentObserver::class);

        Blade::directive('numToWords', function ($expression) {
            return "<?php echo app('NumberToWords')->toWords($expression); ?>";
        });

    }
}
