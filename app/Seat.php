<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }
}
