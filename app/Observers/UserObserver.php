<?php

namespace App\Observers;

use App\Helpers\Helper;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if (Helper::needApiValidation()) {
            Mail::to($user)->send(new UserCreated($user));
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
        if (Helper::needApiValidation()) {
            /** Illuminate\Database\Eloquent\Model $user */
            if ($user->isDirty('email')) {
                Mail::to($user)->send(new UserMailChanged($user));
                $minutes = intval(User::MINUTES_TO_RESEND);
                $user->next_resend_at = Carbon::now()->addMinutes($minutes);
                $user->save();
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
