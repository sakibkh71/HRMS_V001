<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDetails extends Model
{
	protected $table = 'loan_details';
    protected $fillable = ['user_id', 'loan_id', 'amount','salary_month','date','created_by','updated_by', 'created_at', 'updated_at'];

    public function user(){
   		return $this->belongsTo('App\Models\User'); 
   }
}
