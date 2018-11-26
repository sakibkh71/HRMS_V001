<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeExperienceRequest extends FormRequest
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
            'company_name' => 'required|alpha_spaces',
            'position_held' => 'required|alpha_spaces',
            'job_start_date' => 'required|date',
            'job_end_date' => 'required|date',
            'job_duration' => 'required',
            'job_responsibility' => 'required',
            'job_location' => 'required',
        ];
    }
}
