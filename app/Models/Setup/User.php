<?php

namespace App\Models\Setup;

use App\Notifications\SetupResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'first_name','last_name','email','password','mobile_number','user_type', 'status'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SetupResetPasswordNotification($token));
    }

    public function getFullNameAttribute(){
        return ucfirst($this->first_name). ' ' .ucfirst($this->last_name);
    }

    public function payments(){

        return $this->hasMany('App\Setup\Payment');
    }

}
