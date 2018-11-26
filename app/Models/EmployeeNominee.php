<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeNominee extends Model
{
    protected $fillable = ['user_id','nominee_name','nominee_relation','nominee_address','nominee_birth_date','nominee_distribution','nominee_rest_distribution','nominee_photo','created_by'];
}
