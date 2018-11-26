<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryAccount extends Model
{
    protected $fillable = ['user_id','bank_id','bank_account_no','bank_account_name','bank_branch_name','bank_branch_address','created_by','updated_by'];
}
