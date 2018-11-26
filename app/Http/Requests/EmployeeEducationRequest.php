<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeEducationRequest extends FormRequest
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
            'education_level_id' => 'required',
            'institute_id' => 'required',
            'degree_id' => 'required',
            'pass_year' => 'required',
            'certificate_file' => 'nullable|mimes:jpeg,jpg,png,pdf|max:4000',
            'result_type' => 'required',
            'cgpa' => 'required_if:result_type,cgpa|numeric|max:5',
            'division' => 'required_if:result_type,division',
        ];
    }

    public function messages()
    {
        return [
            'certificate_file.max' => 'The file size must be less then 4 MB.'
        ];
    }
}
