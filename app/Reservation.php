<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string email
 */
class Reservation extends Model
{
    const RESERVED = 1;
    const PAID = 2;

    public $fillable = ['email', 'status_id'];

    public function seats()
    {
        return $this->belongsToMany(Seat::class);
    }

    /**
     * @param array $seatIds
     * @return bool
     */
    public static function isExistsWithSeats(array $seatIds)
    {
        return static::query()
            ->where(function(Builder $query){
                $query->where('status_id', static::PAID);
            })
            ->orWhere(function(Builder $query){
                $query->where('status_id', static::RESERVED)
                    ->where('created_at', '<', now()->addMinutes(2));
            })
        ->whereHas('seats', function(Builder $query) use ($seatIds) {
            $query->whereIn('id', $seatIds);
        })->exists();
    }
}
