<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAddress extends Model
{

    public $primaryKey = 'id';

    protected $table = 'employee_address';

    protected $fillable = ['user_id','present_division_id','present_district_id','present_policestation_id','present_postoffice','present_address',
        'permanent_division_id','permanent_district_id','permanent_policestation_id','permanent_postoffice','permanent_address','created_by','updated_by'
    ];


    public static function findUser($user_id){
        return static::where('user_id', $user_id)->first();
    }


    public function presentDivision(){
    	return $this->belongsTo('App\Models\Division','present_division_id','id');
    }


    public function presentDistrict(){
    	return $this->belongsTo('App\Models\District','present_district_id','id');
    }


    public function presentPoliceStation(){
    	return $this->belongsTo('App\Models\PoliceStation','present_policestation_id','id');
    }


    public function permanentDivision(){
    	return $this->belongsTo('App\Models\Division','permanent_division_id','id');
    }

    public function permanentDistrict(){
    	return $this->belongsTo('App\Models\District','permanent_district_id','id');
    }

    public function permanentPoliceStation(){
    	return $this->belongsTo('App\Models\PoliceStation','permanent_policestation_id','id');
    }


}
