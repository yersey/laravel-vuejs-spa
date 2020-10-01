<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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

    public function rules()
    {
        return [
            'message' => 'required|min:1|max:1024',
            'to_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'message.required'  => 'Treść jest wymagana.',
            'message.min'  => 'Treść jest zbyt krótka.',
            'message.max'  => 'Treść jest zbyt długa.',
        ];
    }
}
