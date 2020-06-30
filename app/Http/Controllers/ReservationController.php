<?php

namespace App\Http\Controllers;

use App\Events\ReservationHasFinished;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Reservation;

class ReservationController extends Controller
{
    /**
     * @param ReservationRequest $request
     * @return ReservationResource
     */
    public function store(ReservationRequest $request)
    {
        $reservation = Reservation::query()->create($request->all());

        $reservation->seats()->sync($request->get('selectedSeats'));

        return new ReservationResource($reservation);
    }

    /**
     * @param Reservation $reservation
     * @return bool
     */
    public function update(Reservation $reservation)
    {
        $success = $reservation->update(['is_paid' => true]);

        event(new ReservationHasFinished($reservation));

        return $success;
    }
}
