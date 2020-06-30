<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string email
 * @property string is_paid
 * @property Carbon created_at
 * @property string updated_at
 */
class Reservation extends Model
{
    public $fillable = ['email', 'is_paid'];

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
                $query->where('is_paid', true);
            })
            ->orWhere(function(Builder $query){
                $query->where('is_paid', false)
                    ->where('created_at', '>=', now()->subMinutes(config('app.time_limit')));
            })
        ->whereHas('seats', function(Builder $query) use ($seatIds) {
            $query->whereIn('id', $seatIds);
        })->exists();
    }
}
