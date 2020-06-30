<?php

namespace App\Http\Resources;

use App\Seat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Seat
 */
class SeatResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'reservations' => ReservationResource::collection($this->whenLoaded('reservations')),
            'statusId' => $this->whenLoaded('reservations', $this->getStatusId())
        ];
    }
}
