<?php

namespace App\Http\Requests\Tour;

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
            'price' => [ 'required', 'numeric' ],
            'start_date' => [ 'required', 'date' ],
            'end_date' => [ 'required', 'date', 'after_or_equal:start_date' ],
        ];

        return $rules;
    }
}
