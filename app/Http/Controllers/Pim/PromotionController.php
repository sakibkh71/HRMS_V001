<?php

namespace App\Http\Controllers\Pim;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use App\Models\Promotion;
use App\Models\User;
use App\Models\Designation;
use App\Models\Level;

class PromotionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['getPromotionsData','getSingelUser']]);

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function findParent($levelId, $parentAry){
            
        $val = Level::where('id',$levelId)->first();

        if($val->parent_id > 0){

            array_push($parentAry, $val->parent_id);
            // echo $val->parent_id." *-*";
            return $this->findParent($val->parent_id, $parentAry);
        }

        return $parentAry;
    }

    public function index(){
        
    	$data['title'] = "Promotions/Transfer";
        $data['sidevar_hide'] = 1;
        return view('pim.promotion', $data);
    }

    public function getPromotionsData(){

        return Promotion::with('user','prev_designation.department','current_designation.department','prev_designation.level','current_designation.level','prev_branch','current_branch','prev_unit.promotionDepartment','current_unit.promotionDepartment','prev_supervisor','current_supervisor')->orderBy('id','DESC')->get();
    }

    public function getSingelUser($id){

        $data['multi_values'] = User::with('branch', 'designation.department','designation.level', 'unit.department', 'supervisor')->where('id', $id)->first();

        //get existing designation .. get existing level ... get upper level
        //using level whereIn designation
        $designationId = $data['multi_values']->designation_id;
        $val = Designation::find($designationId);
        $parentAry = [];
        $parentAry = $this->findParent($val->level_id, $parentAry);
        array_push($parentAry , $val->level_id); //push same level id

        $data['to_designation'] = Designation::with('department.units','level')->whereIn('level_id', $parentAry)->get();
        
        foreach($data['to_designation'] as $val){
            $designationIdAry[] = $val->id;
        }

        $data['to_supervisor'] = User::whereIn('designation_id', $designationIdAry)->where('id','!=',$id)->get();

        return $data;
    }

    public function create(Request $request){

        $this->validate($request, [
            'form_type' => 'required',
            'user_id' => 'required',
            'effective_date' => 'required',
        ],
        [
            'user_id.required'     => 'Please select a user first.',
        ]);

        try{
            $data['data'] = Promotion::create([
                'user_id' => $request->user_id,
                'from_supervisor_id' => $request->from_supervisor_id ,
                'to_supervisor_id' => ($request->to_supervisor_id > 0)?$request->to_supervisor_id:$request->from_supervisor_id ,
                'from_branch_id' => $request->from_branch_id ,
                'to_branch_id' => ($request->to_branch_id > 0)?$request->to_branch_id:$request->from_branch_id ,
                'from_designation_id' => $request->from_designation_id ,
                'to_designation_id' => ($request->to_designation_id > 0)?$request->to_designation_id:$request->from_designation_id ,
                'from_unit_id' => $request->from_unit_id ,
                'to_unit_id' => ($request->to_unit_id > 0)?$request->to_unit_id:$request->from_unit_id ,
                'transfer_effective_date' => $request->effective_date ,
                'promotion_type' => $request->form_type ,
                'promotion_status' => 1 ,
                'remarks' => $request->remarks ,
                'created_by' => Auth::user()->id ,
            ]);
        
            $data['title'] = 'success';
            $data['message'] = 'data successfully added!';

        }catch (\Exception $e) {
            
           $data['title'] = 'danger';
            $data['message'] = 'data not added!';
        }

        return response()->json($data);
    }

    public function update(Request $request){

        $this->validate($request, [
            'edit_form_type' => 'required',
            'edit_effective_date' => 'required',
        ]);

        try{
            $data['data'] = Promotion::where('id',$request->hdn_id)->update([
                'to_supervisor_id' => ($request->edit_to_supervisor_id > 0)?$request->edit_to_supervisor_id:$request->edit_from_supervisor_id ,
                'to_branch_id' => ($request->edit_to_branch_id > 0)?$request->edit_to_branch_id:$request->edit_from_branch_id ,
                'to_designation_id' => ($request->edit_to_designation_id > 0)?$request->edit_to_designation_id:$request->edit_from_designation_id ,
                'to_unit_id' => ($request->edit_to_unit_id > 0)?$request->edit_to_unit_id:$request->edit_from_unit_id ,
                'transfer_effective_date' => $request->edit_effective_date ,
                'promotion_type' => $request->edit_form_type ,
                'promotion_status' => 1 ,
                'remarks' => $request->edit_remarks ,
                'updated_by' => Auth::user()->id ,
            ]);
        
            $data['title'] = 'success';
            $data['message'] = 'data successfully updated!';

        }catch (\Exception $e){
            
            $data['title'] = 'danger';
            $data['message'] = 'data not added!';
        }

        return response()->json($data);
    }
}
