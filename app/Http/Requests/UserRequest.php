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
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone' => 'nullable|min:3|max:12',
            'profile_photo' => 'mimes:jpeg,png,jpg|max:1999',
            [ // Custom Messages
                'name.regex' => 'Name must only contain letters and spaces.',
            ]
        ];
    }
}
