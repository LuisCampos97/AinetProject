<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidValue;

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
            'movement_category_id' => 'required|numeric|exists:movement_categories,id',
            'date' => 'required|date',
            'value' => ['required','numeric', new ValidValue],
            'description' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,png,jpeg|required_with:document_description',
            'document_description' => 'nullable|string'
        ];
    }
}
