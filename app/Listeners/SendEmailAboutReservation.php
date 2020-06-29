<?php

namespace App\Listeners;

use App\Events\ReservationHasFinished;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailAboutReservation implements ShouldQueue
{
    public function handle(ReservationHasFinished $event)
    {
        $email = $event->reservation->email;
    }
}
