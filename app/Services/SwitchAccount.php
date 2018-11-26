<?php 

namespace App\Services;

use App\Models\User;
use App\Models\Level;
use App\Models\Department;
use App\Models\Branch;
use App\Models\Designation;
use App\Models\Units;
use App\Models\EmployeeType;
use App\Models\UserEmployeeTypeMap;

use App\Services\CommonService;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

trait SwitchAccount
{
	use CommonService;

    private function switchAccountLogin($database_name, $config_id, $user_id){
    	
    	Artisan::call('db:connect',['database' => $database_name]);

    	if(Auth::guard('hrms')->loginUsingId($user_id)){
    		Session(['database'=>$database_name, 'config_id' => $config_id]);
    		$this->settings();
    		Session::flash('success','Account successfully switch.');
    	}else{
    		Session::flash('danger','Account not switch.');
    	}

    	return redirect()->back();
    }


    private function switchAccountRegister($database_name, $config_id, $user_id=null){

    	Artisan::call('db:connect',['database' => Session('database')]);

    	$userInfo = User::with('employeeTypeMap')->find($user_id);

		$employee_type_id = $userInfo->employee_type_id;
    	$branch_id = $userInfo->branch_id;
    	$designation_id = $userInfo->designation_id;
    	$unit_id = $userInfo->unit_id;

    	Artisan::call('db:connect',['database' => $database_name]);

    	$branchData = Branch::find($branch_id);
    	$desData = Designation::find($designation_id);
    	$unitData = Units::find($unit_id);

    	$branch_id 		= (count($branchData) > 0)?$branch_id:1;
    	$designation_id = (count($desData) > 0)?$designation_id:1;
    	$unit_id 		= (count($unitData) > 0)?$unit_id:1;

    	DB::beginTransaction();

    	try{
	    	if($user = User::create([
			    "employee_no" => $userInfo->employee_no,
			    "employee_type_id" => $employee_type_id,
			    "branch_id" => $branch_id,
			    "designation_id" => $designation_id,
			    "unit_id" => $unit_id,
			    "basic_salary" => $userInfo->basic_salary,
			    "salary_in_cache" => $userInfo->salary_in_cache,
			    "effective_date" => $userInfo->employeeTypeMap->from_date,
			    "first_name" => $userInfo->first_name,
			    "middle_name" => $userInfo->middle_name,
			    "last_name" => $userInfo->last_name,
			    "nick_name" => $userInfo->nick_name,
			    "email" => $userInfo->email,
			    "password" => $userInfo->password,
			    "status" => 55,
			    "mobile_number" => $userInfo->mobile_number,
			    "photo" => $userInfo->photo,
			    "company_ids" => $userInfo->company_ids,
	    		]))
	    	{
	    		$save = new UserEmployeeTypeMap;
	    		$save->user_id = $user->id;
	    		$save->employee_type_id = $employee_type_id;
	    		$save->from_date = $userInfo->employeeTypeMap->from_date;
	    		$save->to_date = $userInfo->employeeTypeMap->to_date;
	    		$save->save();

	    		DB::commit();

                //copy photo
                if(Storage::disk('public')->exists(Session('config_id').'/'.$userInfo->id.'/'.$userInfo->photo)){
                    
                    Storage::disk('public')->copy(Session('config_id').'/'.$userInfo->id.'/'.$userInfo->photo, $config_id.'/'.$user->id.'/'.$userInfo->photo);
                }
	    	}

	    	Session::flash('success','Data updated successfully!');

	    }catch(\Exception $e){
	    	$code = $e->getCode();

	    	// if($code == '23000'){
	    	// 	$message  = $e->getMessage();

	    	// 	if(stristr($message,'REFERENCES')){
	    	// 		$msgData = explode(' ', $message);
	    	// 		$reference_key = array_search('REFERENCES',$msgData);
	    	// 		$table =  str_replace('`','',$msgData[$reference_key+1]);
	    	// 	}

	    	// 	if(isset($table)){
	    	// 		Artisan::call('db:connect',['database' => Session('database')]);
	    	// 		$this->setDependanceyData($table);
	    	// 	}
	    	// }
	    	DB::rollback();

            Session::flash('danger','Account not switch.Try again.');
	    }

	    Artisan::call('db:connect',['database' => Session('database')]);
	    return redirect()->back();
    }

    public function switchAccountActive($user_id){

    	$sav = User::find($user_id);
    	$sav->status = 55;
    	$sav->save();

    	Session::flash('success','Data updated successfully!');
    }

    public function switchAccountInActive($user_id){

    	$sav = User::find($user_id);
    	$sav->status = 54;
    	$sav->save();

    	Session::flash('success','Data updated successfully!');
    }

    private function setDependanceyData($table){
    	if($table == 'employee_types'){
    		$this->getEmployeeType();
		}

		if($table == 'designations'){
			$this->getDesignation();
		}

		if($table == 'branchs'){
			$this->getBranch();
		}

		if($table == 'units'){
			$this->getUnit();
		}

		// $data['employee_type_id'] = $this->getEmployeeType();
  //   	$data['branch_id'] = $this->getBranch();
  //   	$data['designation_id'] = $this->getDesignation();
  //   	$data['unit_id'] = $this->getUnit($data['designation_id']);
    }


    private function getEmployeeType(){

    	if(!EmployeeType::find($this->auth->employee_type_id)){
	    	$employee_type = EmployeeType::orderBy('id','asc')->first();
	    	$employee_type_id = $employee_type->id;
	    }else{
	    	$employee_type_id = $this->auth->employee_type_id;
	    }
	    return $employee_type_id;
    }


    private function getBranch(){

    	if(!Branch::find($this->auth->branch_id)){
    		$branch = Branch::orderBy('id','asc')->first();
    		$branch_id = $branch->id;
		}else{
			$branch_id = $this->auth->branch_id;
		}
		return $branch_id;
    }


    // private function getLevel(){

    // 	if(!Level::find($this->auth->designation->level_id)){
    // 		$level = Level::orderBy('id','asc')->first();
    // 		$level_id = $level->id;
    // 	}else{
    // 		$level_id = $this->auth->designation->level_id;
    // 	}
    // 	return $level_id;
    // }


    private function getDepartment($designation_id){
    	if(!Department::find($designation_id)){
    		$department = Department::orderBy('id','asc')->first();
    		$department_id = $department->id;
    	}else{
    		$department_id = $designation_id;
    	}
    	return $department_id;
    }


    private function getUnit($designation_id){
    	$department_id = $this->getDepartment($designation_id);

    	if(Units::where('unit_departments_id',$department_id)->count() <=0 ){
    		$unit = Units::orderBy('id','asc')->first();
    		$unit_id = $unit->id;
    	}else{
    		$unit_id = $department_id;
    	}
    	return $unit_id;
    }


    private function getDesignation(){
    	
    	if(!Designation::find($this->auth->designation_id)){
	    	$designation = Designation::orderBy('id','asc')->first();
    		$designation_id = $designation->id;
	    }else{
	    	$designation_id = $this->auth->designation->id;
	    }

	    return $designation_id;
    }

}