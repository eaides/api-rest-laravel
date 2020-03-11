<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        // Passport::enableImplicitGrant() IS NOT A SECURE METHOD, disabled after TESTs
        // Passport::enableImplicitGrant();
        Passport::tokensCan([
            'purchase-products' => 'can create transaction to buy determinated products',
            'manage-products' => 'create, view, modify and delete products',
            'manage-account' => 'obtain info  of the account like name, email,state but no password.
                Can modify datos (include password) but NO delete the account',
            'manage-sections' => 'create, view, modify and delete sections',
            'manage-posts' => 'create, view, modify and delete posts',
            'read-general' => 'read information, categories where buy and sell, 
                products selled or buyed, transactions, etc',
        ]);
    }
}
