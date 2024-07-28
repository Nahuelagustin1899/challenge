<?php

namespace App\Http\Requests\Tour;

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
            'name' => [ 'nullable', 'string', 'max:255' ],
            'description' => [ 'nullable', 'string' ],
            'price' => [ 'nullable', 'numeric' ],
            'min_price' => [ 'nullable', 'numeric' ],
            'max_price' => [ 'nullable', 'numeric' ],
            'start_date' => [ 'nullable', 'date' ],
            'end_date' => [ 'nullable', 'date' ],
            'start_date_from' => [ 'nullable', 'date' ],
            'end_date_until' => [ 'nullable', 'date' ],
        ];

        return array_merge( $rules, App::DEFAULT_LIST_REQUEST_RULES );

    }
}
