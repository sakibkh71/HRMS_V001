<?php

namespace App\Http\Controllers\Setup\Auth;

use App\Http\Controllers\Controller;
use App\Services\Setup\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*ResetPasswordController
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:setup');
    }
}
