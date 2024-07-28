<?php

namespace App\Http\Requests\Hotel;

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
            'name' => [ 'sometimes', 'string', 'max:255' ],
            'description' => [ 'sometimes', 'string' ],
            'address' => [ 'sometimes', 'string' ],
            'rating' => [ 'sometimes', 'numeric', 'between:1,5' ],
            'price_per_night' => [ 'sometimes', 'numeric' ],
        ];

        return $rules;
    }
}
