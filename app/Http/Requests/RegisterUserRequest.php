<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'firstname'   =>  "required|max:50",

            'lastname'     =>  "required|max:70",

            'email'     => "required|email|max:255|unique:users,email",

            'phone'     =>  "required|numeric|min:11|unique:users,phone",

            'password'  =>   'required|min:8|confirmed',

            'password_confirmation' => 'required',
        ];
    }
}
