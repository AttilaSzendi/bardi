<?php

namespace App\Http\Requests;

use App\Rules\NotInProgress;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'selectedSeats' => ['required', 'array', new NotInProgress]
        ];
    }
}
