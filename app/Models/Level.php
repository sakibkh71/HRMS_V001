<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['level_name','description','level_effective_date'];

    public function salaryInfo(){
    	return $this->hasMany('App\Models\LevelSalaryInfoMap', 'level_id', 'id');
    }

    public function parent(){
        return $this->belongsTo('App\Models\Level');
    }

    public function parentRecursive(){
        return $this->parent()->with('parentRecursive');
    }

    public function child(){
        return $this->hasMany('App\Models\Level','parent_id','id');
    }

    public function childRecursive(){
        return $this->child()->with('childRecursive.designation.user');
    }

    public function designation(){
    	return $this->hasMany('App\Models\Designation');
    }

    public function levelPermission(){
        return $this->hasMany('App\Models\LevelPermission', 'level_id', 'id');
    }
}
