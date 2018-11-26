<?php

namespace App\Http\Controllers\Pim;

use App\Models\Units;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UnitController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['getUnits']]);

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(){

    	$data['title'] = "Unit";
    	return view('pim.unit', $data);
    }

    public function getUnits(){
        
        return Units::with('department', 'parent')->orderBy('id','DESC')->get();
    }

    public function create(Request $request){

        $this->validate($request, [
            'unit_name' => 'required',
            'unit_department_id' => 'required',
            'unit_status' => 'required',
            'chk_parent' => 'nullable',
            'unit_parent_id' => 'required_if:chk_parent,1',
        ],
        [
            'unit_department_id.required' => 'The unit department field is required.',
            'unit_parent_id.required_if'     => 'The unit parent field is required.',
        ]);

        if($request->chk_parent == 1){

            $unit_parent_id = $request->unit_parent_id;
        }
        else{
            $unit_parent_id = 0;
        }

        try{
            $data['data'] = Units::create([
                'unit_name' => $request->unit_name,
                'unit_departments_id' => $request->unit_department_id,
                'unit_details' => empty($request->unit_details)?'No value':$request->unit_details,
                'unit_effective_date' => $request->unit_effective_date,
                'unit_status' => $request->unit_status,
                'unit_parent_id' => $unit_parent_id,
            ]);
        
            $data['title'] = 'success';
            $data['message'] = 'unit successfully added!';

        }catch (\Exception $e) {
            
            $data['title'] = 'danger';
            $data['message'] = 'unit not added!';
        }

        return response()->json($data);
    }

    public function update(Request $request){

        $this->validate($request, [
            'hdn_id' => 'required',
            'edit_unit_name' => 'required',
            'edit_unit_department_id' => 'required',
            'edit_unit_status' => 'required',
            'chk_parent' => 'nullable',
            'edit_unit_parent_id' => 'required_if:chk_parent,1',
        ],
        [
            'edit_unit_department_id.required' => 'The unit department field is required.',
            'edit_unit_parent_id.required_if'     => 'The unit parent field is required.',
        ]);

        if($request->chk_parent == 1){

            $edit_unit_parent_id = $request->edit_unit_parent_id;
        }
        else{
            $edit_unit_parent_id = 0;
        }


        try{

            $data['data'] = Units::where('id',$request->hdn_id)->update([
                'unit_name' => $request->edit_unit_name,
                'unit_departments_id' => $request->edit_unit_department_id,
                'unit_details' => empty($request->edit_unit_details)?'No value':$request->edit_unit_details,
                'unit_effective_date' => $request->edit_unit_effective_date,
                'unit_status' => $request->edit_unit_status,
                'unit_parent_id' => $edit_unit_parent_id,
            ]);
        
            $data['title'] = 'success';
            $data['message'] = 'unit successfully updated!';

        }catch (\Exception $e) {
            
            $data['title'] = 'danger';
            $data['message'] = 'unit not added!';
        }

        return response()->json($data);
    }

    public function delete($id, $indexId){
        
        $data['indexId'] = $indexId;

        try{
            $data['data'] = Units::find($id)->delete();
        
            $data['title'] = 'success';
            $data['message'] = 'unit successfully removed!';

        }catch(\Exception $e){
            
            $data['title'] = 'danger';
            $data['message'] = 'unit not removed!';
        }

        return response()->json($data);
    }
}
