<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeNomineeRequest extends FormRequest
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
            'user_id' => 'required',
            'nominee_name' => 'required',
            'nominee_relation' => 'required',
            'nominee_birth_date' => 'required|date',
            'nominee_distribution' => 'required|numeric|max:100',
            'nominee_address' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,pdf|max:4000',
        ];
    }


    public function messages(){
        return [
            'image.max' => 'The file size must be less then 4 MB.'
        ];
    }
}
