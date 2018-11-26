<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['department_name', 'department_details', 'department_effective_date'];


    public function units(){
    	return $this->hasMany('App\Models\Units','unit_departments_id','id')->where('unit_status',1);
    }

    public function designations(){
    	return $this->hasMany('App\Models\Designation','department_id','id')->where('status',1);
    }
}
