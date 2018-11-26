<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{

    protected $fillable = ['user_id','institute_id','degree_id','achievement','pass_year','result_type','cgpa','division','certificate','created_by','updated_by'];


    public function institute(){
    	return $this->belongsTo('App\Models\Institute');
    }


    public function degree(){
    	return $this->belongsTo('App\Models\Degree');
    }
}
