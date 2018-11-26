<?php

namespace App\Http\Controllers\Pim;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['getBranch']]);

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(){

    	$data['title'] = "Company Branch";
    	return view('pim.branch', $data);
    }

    public function getBranch(){

        return Branch::orderBy('id', 'DESC')->get();
    }

    public function create(Request $request){

        $this->validate($request, [
            'branch_name' => 'required',
            'branch_mobile' => 'required',
            'branch_location' => 'required',
        ]);

        try{

            $data['data'] = Branch::create([
                'branch_name' => $request->branch_name,
                'branch_email' => $request->branch_email,
                'branch_mobile' => $request->branch_mobile,
                'branch_phone' => $request->branch_phone,
                'branch_location' => $request->branch_location,
                'branch_description' => $request->branch_description,
                'branch_effective_date' => $request->branch_effective_date,
                'branch_status' => $request->branch_status,
            ]);
        
            $data['title'] = 'success';
            $data['message'] = 'branch successfully added!';

        }catch (\Exception $e) {
            
           $data['title'] = 'danger';
            $data['message'] = 'branch not added!';
        }

        return response()->json($data);
    }

    public function update(Request $request){

        $this->validate($request, [
            'hdn_id' => 'required',
            'edit_branch_name' => 'required',
            'edit_branch_mobile' => 'required',
            'edit_branch_location' => 'required',
        ]);

        try{

            $data['data'] = Branch::where('id',$request->hdn_id)->update([
                'branch_name' => $request->edit_branch_name,
                'branch_email' => $request->edit_branch_email,
                'branch_mobile' => $request->edit_branch_mobile,
                'branch_phone' => $request->edit_branch_phone,
                'branch_location' => $request->edit_branch_location,
                'branch_description' => $request->edit_branch_description,
                'branch_effective_date' => $request->edit_branch_effective_date,
                'branch_status' => $request->edit_branch_status,
            ]);
        
            $data['title'] = 'success';
            $data['message'] = 'branch successfully updated!';

        }catch (\Exception $e) {
            
            $data['title'] = 'danger';
            $data['message'] = 'branch not added!';
        }

        return response()->json($data);
    }

    public function delete($id, $indexId){
        
        $data['indexId'] = $indexId;

        try{
            $data['data'] = Branch::find($id)->delete();
        
            $data['title'] = 'success';
            $data['message'] = 'data successfully removed!';

        }catch(\Exception $e){
            
            $data['title'] = 'danger';
            $data['message'] = 'data not removed!';
        }

        return response()->json($data);
    }
}
