<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\AdminNewOrganizationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class AdminSendNewOrganization
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $admin = new User();
        $admin->email = 'admin@correctcare.co.uk';
        $admin->notify(new AdminNewOrganizationNotification($event->user));
    }
}
