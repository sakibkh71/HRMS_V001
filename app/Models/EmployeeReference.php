<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeReference extends Model
{
    protected $fillable = ['user_id','reference_name','reference_email','reference_department','reference_organization','reference_phone','reference_address','created_by'];
}
