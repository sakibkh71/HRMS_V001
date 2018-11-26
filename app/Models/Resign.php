<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resign extends Model
{
    protected $fillable = ['user_id','reason', 'effective_date', 'supervisor_status', 'supervisor_id', 'resign_status'];

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function supervisor(){
    	return $this->belongsTo('App\Models\User','supervisor_id', 'id');
    }
}