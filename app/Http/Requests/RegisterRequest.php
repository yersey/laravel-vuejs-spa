<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|min:2|max:24|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:32|confirmed',
        
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Podaj nick',
            'name.min' => 'Nick jest za krótki',
            'name.max' => 'Nick jest za długi',
            'name.unique' => 'Nick jest zajęty',
            'email.required' => 'Podaj email',
            'email.email' => 'Podaj prawdziwy email',
            'email.unique' => 'Ten adres email jest zajęty',
            'password.required' => 'Podaj hasło',
            'password.min' => 'Hasło jest za krótkie',
            'password.max' => 'Hasło jest za długie',
            'password.confirmed' => 'Hasła nie są takie same',
        ];
    }
}
