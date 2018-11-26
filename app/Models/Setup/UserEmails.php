<?php

namespace App\Models\Setup;

use Illuminate\Database\Eloquent\Model;

class UserEmails extends Model
{
    protected $table = "user_emails";
    
    protected $fillable = [
    	'config_id','email'
    ];
}
