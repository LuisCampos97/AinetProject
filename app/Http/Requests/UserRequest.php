<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email,'. Auth::user()->id,
            'phone' => 'nullable|regex:/^(\+?)([0-9] ?){9,20}$/',
            'profile_photo' => 'nullable|file|mimes:jpeg,png,jpg|max:3000',
            [ // Custom Messages
            'name.regex' => 'Name must only contain letters and spaces.',
            'phone.regex' => 'Format: (+351) xxx xxx xxx',
            ]
        ];
    }
}
