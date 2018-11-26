<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLanguage extends Model
{
    protected $fillable = ['user_id','language_id','speaking','reading','writing','created_by'];


    public function language(){
        return $this->belongsTo('App\Models\Language');
    }
}
