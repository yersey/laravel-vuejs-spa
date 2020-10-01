<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|max:70|min:2',
            'body' => 'required|min:5|max:256',
            'imgurl' => 'required|url',
            'tags' => 'nullable|regex:/^[\pL\s]+$/u',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tytuł jest wymagany.',
            'title.min' => 'Tytuł jest zbyt krótki',
            'title.max' => 'Tytuł jest zbyt długi',
            'body.required'  => 'Treść jest wymagana.',
            'body.min'  => 'Treść jest zbyt krótka.',
            'body.max'  => 'Treść jest zbyt długa.',
            'imgurl.required'  => 'Link do obrazka jest wymagany.',
            'imgurl.url'  => 'Link do obrazka jest nieprawidłowy.',
            'tags.regex'  => 'W tagach znajduja się niedozwolone znaki.',
        ];
    }
}
