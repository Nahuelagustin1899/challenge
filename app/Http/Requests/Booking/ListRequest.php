<?php

namespace App\Http\Requests\Booking;

use App\Enums\App;
use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
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
            'tour_id' => [ 'nullable', 'integer', 'exists:tours,id' ],
            'hotel_id' => [ 'nullable', 'integer', 'exists:hotels,id' ],
            'customer_name' => [ 'nullable', 'string', 'max:255' ],
            'customer_email' => [ 'nullable', 'email', 'max:255' ],
            'number_of_people' => [ 'nullable', 'integer', 'min:1' ],
            'booking_date' => [ 'nullable', 'date' ],
            'tour_name' => [ 'nullable', 'string' ],
            'hotel_name' => [ 'nullable', 'string' ],
            'end_date' => [ 'nullable', 'string' ],
            'start_date' => [ 'nullable', 'string' ]
        ];
        
        return array_merge( $rules, App::DEFAULT_LIST_REQUEST_RULES );

    }
}
