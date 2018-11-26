<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeChildrenRequest extends FormRequest
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
            'children_name' => 'required|alpha_spaces',
            'children_education_level' => 'required|alpha_spaces',
            'children_birth_date' => 'required|date',
            'children_gender' => 'required',
            'children_remarks' => 'required',
        ];

    }
}
