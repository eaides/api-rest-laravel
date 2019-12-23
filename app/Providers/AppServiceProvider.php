<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use App\User;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;

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
        $disable_web_routes = config('app.disable_web_routes');
        $pattern =  config('app.api_prefix') . '/*';
        if ($disable_web_routes || $this->app->request->is($pattern)) {
            if (config('app.use_email_verification'))
            {
                // register sent emails for users events
                User::created(function($user)
                {
                    Mail::to($user)->send(new UserCreated($user));
                });

                User::updated(function($user)
                {
                    /** Illuminate\Database\Eloquent\Model $user */
                    if ($user->isDirty('email'))
                    {
                        Mail::to($user)->send(new UserMailChanged($user));
                    }
                });

            }
        }
    }
}
