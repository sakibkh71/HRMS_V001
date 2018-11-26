<?php

namespace App\Http\Controllers\Setup;

use App\Models\Setup\User as SetupUser;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth:setup');
    }


    public function index(){

		if(Auth::user('setup')->user_type == 2){
			$userDetails = new UserDetailsController;
			return $userDetails->index();
		}


    	$data['title'] = "Dashboard-HRMS";
    	$data['users'] = SetupUser::all();
    	
    	return view('setup.dashboard', $data);
    }
}
