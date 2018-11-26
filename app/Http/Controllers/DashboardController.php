<?php

namespace App\Http\Controllers;

// use App\Jobs\AttendanceTimesheetJob;

use App\Models\User;
use App\Models\UserEmployeeTypeMap;
use App\Models\EmployeeType;
use App\Models\EmpTypeMapWithEmpStatus;
use App\Models\Department;

use App\Services\CommonService;
use App\Services\PermissionService;
use App\Services\OrganogramService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// ini_set('max_execution_time', 300);
// ini_set('memory_limit', '512M');

class DashboardController extends Controller
{
    use CommonService, PermissionService;

	protected $auth;

    public function __construct(Auth $auth){
        $this->middleware('auth:hrms');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }


    public function index()
    {
        // dispatch(new AttendanceTimesheetJob());

        $after15days = date('Y-m-d', strtotime("+30 days"));
        $today = date('Y-m-d');
        $emp_type_will_changed = [];
        $emp_status_will_changed = [];
        $depWithUserAry = [];

        if(in_array("employee/add", session('userMenuShare'))){
            // echo "admin";
            $all_users = User::where('status', 1)->get();

            $sl_emp = 0;
            $sl_st = 0;

            foreach($all_users as $user){
                
                $userStatus = $this->getEmployeeStatus($user->id);
                
                if(!empty($userStatus['final_or_current_type']->to_date) && $userStatus['final_or_current_type']->to_date <= $after15days && $userStatus['final_or_current_type']->to_date > $today){

                    $emp_type_will_changed[$sl_emp]['name'] = $user->full_name;
                    $emp_type_will_changed[$sl_emp]['no'] = $user->employee_no;
                    $emp_type_will_changed[$sl_emp]['status_will_change'] = $userStatus['final_or_current_type']->to_date;

                    $sl_emp++;
                }  

                $statusCount = count($userStatus['status_history']);

                if($statusCount > 0){

                    if($userStatus['status_history'][$statusCount-1]->from_date > $today){
                        //prev one is current

                        if(!empty($userStatus['status_history'][$statusCount-2]->to_date) && $userStatus['status_history'][$statusCount-2]->to_date <= $after15days && $userStatus['status_history'][$statusCount-2]->to_date > $today){

                            $emp_status_will_changed[$sl_st]['name'] = $user->full_name;
                            $emp_status_will_changed[$sl_st]['no'] = $user->employee_no;
                            $emp_status_will_changed[$sl_st]['status_will_change'] = $userStatus['status_history'][$statusCount-2]->to_date;

                            $sl_st++;
                        }
                    }
                }  

            }

            $depWithUserCount = Department::with('designations', 'designations.user')->where('status', 1)->get();

            $depIndex = 0;
            
            foreach($depWithUserCount as $info){

                $usrCounter = 0;

                foreach($info->designations as $val){
                    foreach($val->user as $usr){
                        if($usr->status == 1){
                            $usrCounter++;   
                        }   
                    }
                }

                $depWithUserAry[$depIndex]['dep_name'] = $info->department_name;
                $depWithUserAry[$depIndex]['user'] = $usrCounter;

                $depIndex++; 
            }
        }
        else{

            $userId = Auth::user()->id;
            $sl_emp = 0;
            $sl_st = 0;

            $userStatus = $this->getEmployeeStatus($userId);
                
            if(!empty($userStatus['final_or_current_type']->to_date) && $userStatus['final_or_current_type']->to_date <= $after15days && $userStatus['final_or_current_type']->to_date > $today){

                $emp_type_will_changed[$sl_emp]['name'] = $user->full_name;
                $emp_type_will_changed[$sl_emp]['no'] = $user->employee_no;
                $emp_type_will_changed[$sl_emp]['status_will_change'] = $userStatus['final_or_current_type']->to_date;

                $sl_emp++;
            }  

            $statusCount = count($userStatus['status_history']);

            if($statusCount > 0){

                if($userStatus['status_history'][$statusCount-1]->from_date > $today){
                    //prev one is current

                    if(!empty($userStatus['status_history'][$statusCount-2]->to_date) && $userStatus['status_history'][$statusCount-2]->to_date <= $after15days && $userStatus['status_history'][$statusCount-2]->to_date > $today){

                        $emp_status_will_changed[$sl_st]['name'] = $user->full_name;
                        $emp_status_will_changed[$sl_st]['no'] = $user->employee_no;
                        $emp_status_will_changed[$sl_st]['status_will_change'] = $userStatus['status_history'][$statusCount-2]->to_date;

                        $sl_st++;
                    }
                }
            }
        }

        // dd($emp_status_will_changed);
        // dd($emp_type_will_changed);

        $data['emp_status_will_changed'] = $emp_status_will_changed; 
        $data['emp_type_will_changed'] = $emp_type_will_changed; 
        $data['depWithUserAry'] = $depWithUserAry; 

        if(Session('config_id')){
            $data['sisterConcern'] = $this->getSisterConcern(Session('config_id'));
            $data['motherConcern'] = $this->getMotherConcern(Session('config_id'));
            $data['getAccessCompanies'] = $this->getAccessCompanies(unserialize(Auth::user()->company_ids));

            Artisan::call("db:connect", ['database' => Session('database')]);

            Session([
                'sisterConcern' => $data['sisterConcern']->toArray(),
                'motherConcern' => $data['motherConcern']->toArray(),
                'getAccessCompanies' => $data['getAccessCompanies']->toArray()
                ]);

        }else{
            $data['sisterConcern'] = [];
            $data['motherConcern'] = [];
            $data['getAccessCompanies'] = [];
        }

        $data['organogram'] = $this->getOrganogram();
        return view('dashboard')->with($data);
    }


    public function getOrganogram(){
        $organogram = new OrganogramService();
        return  $organogram->organogram();
    }


    public function notFound(){
        return view('errors.503');
    }


    public function getEmployeeStatus($id){

        $data = json_decode(json_encode($this->getEmployeeTypesHistory($id)));

        //count this employee's emp type history
        $historyCount = count($data->original->history);

        //Only one upcoming or future type can be added for a emp.

        if($data->original->up_coming_type == 0){
            //no upComing emp type
            //so history last row is the current or final emp type
            $finalOrCurrentType = $data->original->history[$historyCount-1];
        }
        else{
            //this emp hav upComing emp type
            //so current type is last row's prev row
            $finalOrCurrentType = $data->original->history[$historyCount-2];
        }

        $current_type_map_id = $finalOrCurrentType->id;
        $checking = EmpTypeMapWithEmpStatus::where('user_emp_type_map_id', $current_type_map_id)->first();

        //If no data in emp_type_map_with_emp_status it generate data

        if(empty($checking)){

            $emp_type_data = UserEmployeeTypeMap::where('id', $current_type_map_id)->first();

            DB::beginTransaction();

            try {

                $sav = new EmpTypeMapWithEmpStatus;
                $sav->user_emp_type_map_id = $current_type_map_id;
                $sav->employee_status_id = 1;
                $sav->from_date = $emp_type_data->from_date;
                $sav->remarks = "Generated By System";
                $sav->created_by = Auth::user()->id;
                $sav->save();
            
                $savUser = User::find($emp_type_data->user_id)->update(['status' => 1]);

                DB::commit();
            } catch (\Exception $e) {
                
                DB::rollback();
            }
        }

        $current_types_status_history = EmpTypeMapWithEmpStatus::where('user_emp_type_map_id', $current_type_map_id)->get();

        $date = new \DateTime(null, new \DateTimeZone('Asia/Dhaka'));
        $current_date = $date->format('Y-m-d');
        
        //######### find upcomming status --Start ########
        //only one future data canbe save so only last one will be upcomming or not
        $upCommingStatus = 0;
        $lastIndex = count($current_types_status_history);

        if($lastIndex > 0){

            $tempAry = $current_types_status_history[$lastIndex-1];

            if(strtotime($tempAry->from_date) > strtotime($current_date)){

                $upCommingStatus = $tempAry->id;
            }
        }
        //######### find upcomming status --Finished

        //@@******* Check emp Valid or Not **********
        $validityStatus = "Valid";

        if(!empty($finalOrCurrentType->to_date) && (strtotime($finalOrCurrentType->to_date) < strtotime($current_date))) {
            $validityStatus = "Invalid";
        }

        $dataAry['final_or_current_type'] = $finalOrCurrentType;
        $dataAry['validity'] = $validityStatus;
        $dataAry['status_history'] = $current_types_status_history;
        $dataAry['upcomming_status'] = $upCommingStatus;

        return $dataAry;
    }


    public function getEmployeeTypesHistory($id){

        $upCommingType = 0;
        $data['history'] = UserEmployeeTypeMap::where('user_id', $id)->get();

            $date = new \DateTime(null, new \DateTimeZone('Asia/Dhaka'));
            $currentDate = $date->format('Y-m-d');

            $dataa = UserEmployeeTypeMap::where('user_id', $id)->orderBy('id', 'DESC')->take(2)->get();

            if(count($dataa) == 1){
            
                $current_type = $dataa[0]->employee_type_id;
            }
            else{

                if(empty($dataa[0]->to_date) && (strtotime($dataa[0]->from_date) <= strtotime($currentDate))){

                    $current_type = $dataa[0]->employee_type_id;

                }
                elseif((strtotime($dataa[0]->from_date) <= strtotime($currentDate)) && (strtotime($dataa[0]->to_date) >= strtotime($currentDate)) && !empty($dataa[0]->to_date)){

                    $current_type = $dataa[0]->employee_type_id;
                }
                elseif((strtotime($dataa[1]->from_date) <= strtotime($currentDate)) && (strtotime($dataa[1]->to_date) >= strtotime($currentDate)) && !empty($dataa[1]->to_date)){

                    $current_type = $dataa[1]->employee_type_id;
                    $upCommingType = $dataa[0]->id;
                }
                else{
                    // "Invalid"
                    $current_type = $dataa[0]->employee_type_id;
                }
            }


        $data['current_type'] = $current_type;
        $data['up_coming_type'] = $upCommingType; //upComming type can be delete
        $data['emp_types'] = EmployeeType::where('status', 1)->get();

        return response()->json($data); 
    }




}
