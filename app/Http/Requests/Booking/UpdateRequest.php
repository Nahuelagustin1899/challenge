<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'tour_id' => ['sometimes', 'integer', 'exists:tours,id'],
            'hotel_id' => ['sometimes', 'integer', 'exists:hotels,id'],
            'customer_name' => [ 'sometimes', 'string', 'max:255' ],
            'customer_email' => [ 'sometimes', 'email', 'max:255' ],
            'number_of_people' => ['sometimes', 'integer', 'min:1'],
            'booking_date' => ['sometimes', 'date'],
        ];

        return $rules;
    }
}
