<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
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
            'designation_name' => 'required|alpha_spaces',
            'department_id' => 'required',
            'level_id' => 'required',
            'designation_description' => 'required'
        ];
    }


    public function attributes(){
        return [
            'designation_name' => 'Designation Name',
            'department_id' => 'Department',
            'level_id' => 'Level',
            'designation_description' => 'Designation Description'
        ];
    }
}
