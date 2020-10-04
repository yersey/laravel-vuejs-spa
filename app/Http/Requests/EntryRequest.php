<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntryRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|min:2|max:10000',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => 'Podaj treść wpisu',
            'body.min' => 'Wpis jest za krótki',
            'body.max' => 'Wpis jest za długi',
        ];
    }
}
