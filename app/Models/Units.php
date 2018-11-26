<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
	protected $fillable = ['unit_name','unit_parent_id','unit_departments_id','unit_details', 'unit_effective_date','unit_status'];

	public function department(){
		return $this->hasOne('App\Models\Department', 'id', 'unit_departments_id');
	}

	public function promotionDepartment(){
		return $this->belongsTo('App\Models\Department', 'unit_departments_id');
	}

	// each category might have one parent
	public function parent(){
		return $this->belongsTo('App\Models\Units', 'unit_parent_id');
	}
}
