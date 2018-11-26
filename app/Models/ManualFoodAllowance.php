<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualFoodAllowance extends Model
{
	protected $fillable = ['user_id', 'user_mills', 'per_mill_cost', 'total_mill_amount', 'company_pay', 'salary_month', 'created_by'];
    protected $table = 'manual_food_allowance';

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
