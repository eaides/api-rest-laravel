<?php

namespace App\Providers;

use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Product;
use Illuminate\Support\ServiceProvider;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * User observer
         */
        User::observe(UserObserver::class);

        /**
         * Product Observer
         */
        Product::observe(ProductObserver::class);

    }
}
