<?php

namespace App\Http\Controllers\Leave;

use App\Models\User;
use App\Models\Resign;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ResignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:hrms');
        // $this->middleware('CheckPermissions', ['except' => ['changeStatus', 'getWeekendHolidays', 'view', 'details', 'chResponsibleStatus']]);

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(){

    	$data['title'] = "HRMS|Resign";
    
        $data['resignInfos'] = Resign::with('user', 'supervisor')->orderBy('resign_status')->get();

        return view('leave.resign', $data);
    }

    public function create(Request $request){

        $this->validate($request, [
            'emp_name' => 'required',
            'effective_date' => 'required',
            'resign_reason' => 'required',
        ],[
            'emp_name.required' => 'Employee name is required.',
            'effective_date.required' => 'Effective date is required.',
            'resign_reason.required' => 'Reason is required.',
        ]);

        $emp_name = $request->emp_name;
        $effective_date = $request->effective_date;
        $resign_reason = $request->resign_reason;

        $supervisor_id = User::find($emp_name)->supervisor_id;

        DB::beginTransaction();

        try { 
            $sav = new Resign;
            $sav->user_id = $emp_name;
            $sav->reason = $resign_reason;
            $sav->effective_date = $effective_date;
            $sav->supervisor_status = 1;
            $sav->supervisor_id = (count($supervisor_id->toArray()) > 0)?$supervisor_id:'';
            $sav->resign_status = 1;
            $sav->save();

            DB::commit();
            $data['title'] = 'success';
            $data['message'] = 'data successfully added!';
        } 
        catch (\Exception $e){

            DB::rollback();
            $data['title'] = 'error';
            $data['message'] = 'data not added!';
        }

        return $data;
    }

    public function changeStatus($id, $stat){

        $date = new \DateTime(null, new \DateTimeZone('Asia/Dhaka'));

        DB::beginTransaction();

        try { 
            $val = Resign::find($id);
            $val->resign_status = $stat;
            $val->resign_approved_by = Auth::user()->id; 
            //approval_date use for only both Approve or Cancel
            $val->resign_approval_date = $date->format('Y-m-d'); 
            $val->save();

            DB::commit();
        } 
        catch (\Exception $e){

            DB::rollback();
        }
    }
}