<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
//        view()->composer(['ecommerce.vitrine.*', 'ecommerce.index'], function ($view) {
//
//            $cart = session('cart', []);
//            $sum = 0;
//            foreach ($cart as $item) {
//                $sum += $item['price'] * $item['quantity'];
//            }
//
//            $view->with(compact('cart', 'sum'));
//        });
    }
}
