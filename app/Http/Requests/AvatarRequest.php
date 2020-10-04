<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarRequest extends FormRequest
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
            'avatar' => 'required|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'avatar.required' => 'Plik jest wymagany.',
            'avatar.image' => 'Plik nie jest obrazem',
            'avatar.max' => 'Plik jest za duży',
        ];
    }
}
