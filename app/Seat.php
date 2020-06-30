<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection reservations
 */
class Seat extends Model
{
    const FREE = 1;
    const RESERVED = 2;
    const PAID = 3;

    /**
     * @return BelongsToMany
     */
    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class);
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        if($this->reservations->count() === 0) {
            return Seat::FREE;
        }

        if($this->hasPaidReservation()) {
            return Seat::PAID;
        }

        if($this->hasActiveReservation()) {
            return Seat::RESERVED;
        }

        return Seat::FREE;
    }

    /**
     * @return bool
     */
    public function hasActiveReservation(): bool
    {
        return (bool) $this->reservations
            ->where('created_at', '>=', now()->subMinutes(config('app.time_limit')))
            ->count();
    }

    /**
     * @return bool
     */
    public function hasPaidReservation(): bool
    {
        return (bool) $this->reservations->where('is_paid', true)->count();
    }
}
