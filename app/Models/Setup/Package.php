<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = "packages";

    protected $fillable = [
    	'id', 'package_name', 'package_details', 'package_price', 'package_duration', 'package_type','package_sister_concern_limit', 'package_level_limit', 'package_user_limit', 'package_status', 'package_created_by'
    ];

	public function modules(){
		return $this->hasMany('App\Models\Setup\ModulePackageMap');
	}

}
