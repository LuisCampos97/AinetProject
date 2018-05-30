<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class AccountRequest extends FormRequest
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
            'account_type_id' => 'required|integer|min:1',
            'code' => 'required|string|unique:accounts',
            'date' => 'nullable|date', 
            'start_balance' => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }
}
