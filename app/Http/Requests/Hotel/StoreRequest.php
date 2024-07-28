<?php

namespace App\Http\Requests\Hotel;

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
            'name' => [ 'required', 'string', 'max:255' ],
            'description' => [ 'required', 'string' ],
            'address' => [ 'required', 'string' ],
            'rating' => [ 'required', 'numeric', 'between:1,5' ],
            'price_per_night' => [ 'required', 'numeric' ],
        ];

        return $rules;
    }
}
