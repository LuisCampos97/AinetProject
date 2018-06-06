<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidOldPassword;

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
            'old_password' => ['required', new ValidOldPassword],
            'password' => 'required|confirmed|min:3|different:old_password',
            'password_confirmation' => 'required|min:3',
            [
            'old_password.required' => 'Please set your old password',
            'password.different' => 'Please enter a different password than your current one.'
            ]
        ];
    }
}
