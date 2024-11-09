<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CheckUserActiveStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        if (!$event->user->is_active) { // Remplace `active` par le nom de ta colonne
            Auth::logout();
            session()->flash('error', 'Votre compte est désactivé. Veuillez contacter l\'administrateur.');
            return redirect()->route('login')->send();
        }
        return null;
    }
}
