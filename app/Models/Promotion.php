<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{

	protected $fillable = ['user_id','from_supervisor_id','to_supervisor_id','from_branch_id','to_branch_id', 'from_designation_id', 'to_designation_id', 'from_unit_id', 'to_unit_id', 'transfer_effective_date', 'promotion_type', 'promotion_status', 'remarks', 'created_by', 'updated_by'];

	public function user(){
		return $this->belongsTo('App\Models\User', 'user_id');
	}

	public function prev_designation(){
		return $this->belongsTo('App\Models\Designation', 'from_designation_id');
	}

	public function current_designation(){
		return $this->belongsTo('App\Models\Designation', 'to_designation_id');
	}

	public function prev_branch(){
		return $this->belongsTo('App\Models\Branch', 'from_branch_id');
	}

	public function current_branch(){
		return $this->belongsTo('App\Models\Branch', 'to_branch_id');
	}

	public function prev_unit(){
		return $this->belongsTo('App\Models\Units', 'from_unit_id');
	}

	public function current_unit(){
		return $this->belongsTo('App\Models\Units', 'to_unit_id');
	}

	public function prev_supervisor(){
		return $this->belongsTo('App\Models\User', 'from_supervisor_id');
	}

	public function current_supervisor(){
		return $this->belongsTo('App\Models\User', 'to_supervisor_id');
	}
	
}
