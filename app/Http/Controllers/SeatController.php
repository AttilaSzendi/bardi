<?php

namespace App\Http\Controllers;

use App\Http\Resources\SeatResource;
use App\Seat;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SeatController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return SeatResource::collection(
            Seat::query()->with('reservations')->get()
        );
    }

    /**
     * @return SeatResource
     */
    public function store()
    {
        return new SeatResource(
            Seat::query()->create()
        );
    }

    /**
     * @param Seat $seat
     * @return bool|null
     * @throws Exception
     */
    public function destroy(Seat $seat)
    {
        return $seat->delete();
    }
}
