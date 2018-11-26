<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeChildren extends Model
{
    protected $fillable = ['user_id','children_name','children_education_level','children_birth_date','children_gender','children_remarks','created_at'];
}
