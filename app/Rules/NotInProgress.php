<?php

namespace App\Rules;

use App\Reservation;
use Illuminate\Contracts\Validation\Rule;

class NotInProgress implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !Reservation::isExistsWithSeats($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A kiválasztott székek jelenleg nem választhatóak!';
    }
}
