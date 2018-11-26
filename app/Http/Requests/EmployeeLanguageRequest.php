<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeLanguageRequest extends FormRequest
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
            'user_id' => 'required|numeric',
            'language_id' => 'required|numeric',
            'speaking' => 'required',
            'reading' => 'required',
            'writing' => 'required',
        ];
    }


    public function attributes()
    {
        return [
            'language_id' => 'language name'
        ];
    }
}
