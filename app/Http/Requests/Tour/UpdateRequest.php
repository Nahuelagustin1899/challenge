<?php

namespace App\Http\Requests\Tour;

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
            'price' => [ 'sometimes', 'numeric' ],
            'start_date' => [ 'sometimes', 'date' ],
            'end_date' => [ 'sometimes', 'date', 'after_or_equal:start_date' ],
        ];

        return $rules;
    }
}
