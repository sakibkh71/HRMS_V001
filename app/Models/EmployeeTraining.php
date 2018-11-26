<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTraining extends Model
{

    protected $fillable = ['user_id','training_title','training_from_date','training_to_date','training_passed_date','training_participation_date','training_institute','training_remarks','created_by'];
}
