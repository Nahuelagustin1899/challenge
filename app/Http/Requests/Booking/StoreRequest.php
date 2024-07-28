<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $rules = [
            'customer_name' => [ 'required', 'string', 'max:255' ],
            'customer_email' => [ 'required', 'email', 'max:255' ],
            'tour_id' => ['required', 'integer', 'exists:tours,id'],
            'hotel_id' => ['required', 'integer', 'exists:hotels,id'],
            'number_of_people' => ['required', 'integer', 'min:1'],
            'booking_date' => [ 'required', 'date'],
        ];

        return $rules;
    }
}
