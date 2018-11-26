<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelSalaryInfoMap extends Model
{
	protected $table = 'level_salary_info_map';
    protected $fillable = ['level_id', 'basic_salary_info_id', 'amount'];

    public function basicSalaryInfo(){
    	return $this->hasOne('App\Models\BasicSalaryInfo', 'id', 'basic_salary_info_id');
    }

}
