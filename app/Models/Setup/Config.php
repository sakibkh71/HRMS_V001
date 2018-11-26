<?php

namespace App\Models\Setup;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    protected $fillable = [
    	'user_id','company_code','company_name','company_address','database_name','package_end_date','parent_id'
    ];


    public function getCreatedAtAttribute($value){
    	return Carbon::parse($value)->format('M d Y');
    }


    public function sister(){
    	return $this->hasMany('App\Models\Setup\Config','parent_id','id');
    }


    public function mother(){
    	return $this->belongsTo('App\Models\Setup\Config','parent_id');
    }
    
}
