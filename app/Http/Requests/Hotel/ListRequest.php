<?php

namespace App\Http\Requests\Hotel;

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
            'address' => [ 'nullable', 'string' ],
            'rating' => [ 'nullable', 'numeric' ],
            'price_per_night' => [ 'nullable', 'numeric' ],
            'min_rating' => [ 'nullable', 'numeric' ],
            'max_rating' => [ 'nullable', 'numeric' ],
            'min_price' => [ 'nullable', 'numeric' ],
            'max_price' => [ 'nullable', 'numeric' ],
        ];

        return array_merge( $rules, App::DEFAULT_LIST_REQUEST_RULES );
    }
}
