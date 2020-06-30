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
        $request->merge(['is_paid' => false]);

        $reservation = Reservation::query()->create($request->all());

        $reservation->seats()->sync($request->get('selectedSeats'));

        return new ReservationResource($reservation);
    }

    /**
     * @param Reservation $reservation
     * @return Reservation
     */
    public function update(Reservation $reservation)
    {
        $reservation->update(['is_paid' => true]);

        event(new ReservationHasFinished($reservation));

        return $reservation->fresh();
    }
}
