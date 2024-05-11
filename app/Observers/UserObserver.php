<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserDeletedNotification;
use App\Mail\UserForceDeletedNotification;
use App\Mail\UserRestoreNotification;
use Filament\Notifications\Notification;

class UserObserver
{
    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        // Send email notification to the user
        Mail::to($user->email)->send(new UserDeletedNotification($user));
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        // Send email notification to the user
        Mail::to($user->email)->send(new UserRestoreNotification($user));
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        Mail::to($user->email)->send(new UserForceDeletedNotification($user));
    }
}
