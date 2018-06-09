<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidAccountType;
use App\Rules\ValidDate;


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
            'account_type_id' => 'required|numeric|exists:account_types,id',
            'code' => 'required|string|unique:accounts,code',
            'date' => 'nullable|date', 
            'start_balance' => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }
}
