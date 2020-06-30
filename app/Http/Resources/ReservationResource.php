<?php

namespace App\Http\Resources;

use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Reservation
 */
class ReservationResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isPaid' => $this->is_paid,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'seats' => SeatResource::collection($this->whenLoaded('seats')),
        ];
    }
}
