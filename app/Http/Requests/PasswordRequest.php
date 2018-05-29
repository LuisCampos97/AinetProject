<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            'old_password' => 'required|min:3|regex:/^[\pL\s]+$/u',
            'password' => 'required|min:3|confirmed|regex:/^[\pL\s]+$/u',
            'password_confirmation' => 'required|min:3|regex:/^[\pL\s]+$/u',
        ];
    }
}
