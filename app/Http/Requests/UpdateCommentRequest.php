<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
            'body' => 'required|min:1|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => 'Treść jest wymagana.',
            'body.min' => 'Treść jest zbyt krótka.',
            'body.max' => 'Treść jest zbyt długa.',
        ];
    }
}
