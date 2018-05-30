<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovementRequest extends FormRequest
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
            'movement_category_id' => 'required|min:1',
            'type' =>'required|min:1',
            'category' =>'required|min:1',
            'date' => 'required|date',
            'value' => 'required',
            'document_file' => 'nullable|mimes:pdf,jpeg,png'
        ];
    }
}
