<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


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
            'account_type_id' => 'required',
            'code' => 'required|string|unique:accounts,code,'.Auth::user()->id,
            //Rule::unique('accounts')->ignore(Auth::user()->id),
            'date' => 'required|date', //|date_format:"d-m-Y H:i:s"
            'start_balance' => 'required|numeric',
            'description' => 'nullable|string',
        ];
    }
}
