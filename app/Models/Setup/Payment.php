<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payments";

    protected $fillable = [
    	'user_id','config_id','package_id','payment_amount','payment_duration','payment_status'
    ];

    public function package(){

    	return $this->hasOne('App\Models\Setup\Package', 'id', 'package_id');
    }
}
