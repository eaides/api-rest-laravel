<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;

class UserObserver
{
    protected $disable_web_routes;
    protected $use_email_verification;
    protected $is_api = false;

    public function __construct()
    {
        $this->disable_web_routes = config('app.disable_web_routes');
        $this->use_email_verification = config('app.use_email_verification');
        $route_prefix = Route::current()->getPrefix();
        if ($route_prefix == config('app.api_prefix'))
        {
            $this->is_api = true;
        }
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if ($this->disable_web_routes || $this->is_api) {
            if ($this->use_email_verification) {
                Mail::to($user)->send(new UserCreated($user));
            }
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($this->disable_web_routes || $this->is_api) {
            if ($this->use_email_verification) {
                /** Illuminate\Database\Eloquent\Model $user */
                if ($user->isDirty('email')) {
                    Mail::to($user)->send(new UserMailChanged($user));
                }
            }
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
