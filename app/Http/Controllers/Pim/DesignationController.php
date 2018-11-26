<?php

namespace App\Http\Controllers\Pim;

use App\Models\Designation;
use App\Models\Department;
use App\Models\Level;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:hrms');
        $this->middleware('CheckPermissions');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(){

    	$data['title'] = "Employee Designations-HRMS";
        $data['departments'] = Department::where('status',1)->get();
        $data['levels'] = Level::where('status',1)->get();
    	$data['designations'] = Designation::with('department','level')->get();

        return view('pim.designation', $data);
    }

    public function create(Request $request){

    	$this->validate($request, [
		    'name' => 'required',
		    'department' => 'required',
		    'level' => 'required'
		]);

        DB::beginTransaction();

        try {
    	$save = new Designation;
		$save->designation_name = $request->name;
		$save->department_id = $request->department;
		$save->level_id = $request->level;
		$save->designation_description = !empty($request->details)?$request->details:"No description...";
        $save->designation_effective_date = $request->effective_date;
		$save->status = $request->status;
		$save->created_by = Auth::user()->id;
		$save->save();

    	DB::commit();    
        $data = ['title'=>'Success', 'message'=>'Designation successfully added!'];

        } catch (\Exception $e) {
            DB::rollback();
            $data = ['title'=>'Error', 'message'=>'Designation not added!'];
        }

    	return response()->json($data);
    }

    public function edit($id){

    	$info = Designation::find($id);
        
        $data['id']                      = $info->id;
        $data['designation_name']        = $info->designation_name;
        $data['designation_description'] = $info->designation_description;
        $data['designation_effective_date'] = $info->designation_effective_date;
        $data['department_id']           = $info->department_id;
        $data['level_id']                = $info->level_id;
        $data['status']                  = $info->status;

        return $data;
    }

    public function update(Request $request){

		$this->validate($request, [
		    'name' => 'required',
		    'department' => 'required',
		    'level' => 'required'
		]);

        DB::beginTransaction();

        try {
            $save = Designation::find($request->id);
            $save->designation_name = $request->name;
            $save->department_id = $request->department;
            $save->level_id = $request->level;
            $save->designation_description = !empty($request->details)?$request->details:"No description...";
            $save->designation_effective_date = $request->effective_date;
            $save->status = $request->status;
            $save->updated_by = Auth::user()->id;
            $save->save();

            DB::commit();    
            $data = ['title'=>'Success', 'message'=>'Designation successfully updated!'];

        } catch (\Exception $e) {
            DB::rollback();
            $data = ['title'=>'Error', 'message'=>'Designation not updated!'];
        }
    	
    	return response()->json($data);    	
    }

    public function delete(Request $request,$id){

    	$save = Designation::find($id);
		
		if(!empty($save->id)){
			$save->delete();
			$request->session()->flash('success','Designation removed !');
		}
		else{
			$request->session()->flash('danger','Designation not removed !');
		}
		
    	return redirect('designation/index');
    }
}
