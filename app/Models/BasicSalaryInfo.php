<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicSalaryInfo extends Model
{
	protected $table = 'basic_salary_info';
    protected $fillable = ['salary_info_name', 'salary_info_amount', 'salary_info_amount_status','salary_info_type'];
}
