<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTrainingRequest extends FormRequest
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
            // 'training_code' => 'required|numeric|digits_between:5,20',
            'training_title' => 'required|alpha_spaces',
            'training_institute' => 'required|alpha_spaces',
            'training_from_date' => 'required|date',
            'training_to_date' => 'required|date',
            'training_passed_date' => 'required|date',
            'training_participation_date' => 'required|date',
            'training_remarks' => 'required',
        ];
    }



}
