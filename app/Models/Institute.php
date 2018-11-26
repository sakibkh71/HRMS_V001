<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
	protected $fillable = ['education_level_id', 'institute_name', 'status'];
	public $timestamps = false;

    public function educationLevel(){
    	return $this->belongsTo('App\Models\EducationLevel');
    }
}
