<?php

namespace App\Listeners;

use App\Events\MembershipHasExpiredEvent;
use App\Notifications\MembershipExpiredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMembershipExpiredNotificationListener
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
    public function handle(MembershipHasExpiredEvent $event): void
    {
        $event->membership->user->notify(new MembershipExpiredNotification($event->membership));
    }
}
