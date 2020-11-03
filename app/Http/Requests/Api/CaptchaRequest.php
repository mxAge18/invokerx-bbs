<?php

namespace App\Http\Requests\Api;


class CaptchaRequest extends FormRequest
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
            'phone' => [
                'required',
                'regex:/^(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}/',
                'unique:users'
            ]
        ];
    }
}
