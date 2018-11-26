<?php

namespace App\Http\Controllers\Payroll;

use App\Models\User;
use App\Models\ManualFoodAllowance;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManualFoodAllowUploadController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['processData', 'downloadDemo']]);

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(Request $request){

    	$data['infos'] = [];
    	if(!empty($request->salary_month)){

    		$data['infos'] = ManualFoodAllowance::with('user')->where('salary_month', $request->salary_month)->get();
    	}

    	return view('payroll.manually_food_upload', $data);
    }

    public function temp(Request $request){

    	$this->validate($request, [
            'csv_file' => 'required|mimes:csv,txt',
            'salary_month' => 'required',
            'total_tk' => 'required',
        ],[
            'csv_file.required' => 'CSV file required.',
            'salary_month.required' => 'Salary month is required.',
            'total_tk.required' => 'Total coast is required.',
        ]);


        $salary_month = $request->salary_month;
        $total_tk = $request->total_tk;
        $company_pay = $request->company_pay;  //In percent

        $chk = ManualFoodAllowance::where('salary_month', $salary_month)->first();

        if(count($chk) <= 0){

        	//****************Save file start*******************
			$file_name = '';
            if(request()->hasFile('csv_file')){

            	$file = request()->file('csv_file');
            	$exten = $file->getClientOriginalExtension();
            	$file_name = $salary_month.".".$exten;
	        	
            	//=======Delete Old file ==============
            	
                $old_file_folder = "/uploaded_food_csv/".$file_name;
                if(Storage::exists($old_file_folder)){
                    Storage::delete($old_file_folder);
                }

                //=======Save new file =============

                $folder = "/uploaded_food_csv";
                request()->file('csv_file')->storeAs($folder, $file_name);
            }
            //******************Save file end********************

	        $csv = $request->csv_file;
	        $file = fopen($csv->path(), "r");

	        $csvContent = [];
	        $totalMills = 0;

	        $employeeNos = User::select('employee_no', 'id', 'first_name', 'last_name', 'middle_name')->get();

	        while(!feof($file))
	        {
	            $content = fgetcsv($file);
	            $employee_no = trim($content[0]);
	            $mill = trim($content[2]);
	            $totalMills = $totalMills + $mill;

	            $empInfo = $employeeNos->where('employee_no', $employee_no)->first();
	            
	            if(count($empInfo) > 0){
	                $user_id = $empInfo->id;
	                
	            }else{
	                $user_id = null;
	            }

	            if(!empty($user_id) && !empty($mill)){
	                                  
	                $csvContent[] = [
	                    'user_id' => $user_id,
	                    'employee_no' => $employee_no,
	                    'employee_name' => $empInfo->fullname,
	                    'employee_mill' => $mill,
	                ];
	            }
	        }

	        fclose($file);

	        $data['users_ary'] = $csvContent;
	        $data['total_mills'] = $totalMills;
	        $data['total_tk'] = $total_tk;
	        $data['company_pay'] = $company_pay;
	        $data['per_mill_cost'] = sprintf ("%.2f", ($total_tk/$totalMills) );
	        $data['salary_month'] = $salary_month;

	        return view('payroll.manually_food_upload_prev', $data);
	    }
	    else{
	    	$request->session()->flash('danger', 'This month data already exist!');
        	return redirect()->back();
	    }
    }

    public function processData(Request $request){

    	$final_ary = \Session::get('final_ary');
	    
	    if(count($final_ary) > 0){
	    	
	    	$chkMonth = $final_ary[0]['salary_month'];

	    	$chkMonthVal = ManualFoodAllowance::where('salary_month', $chkMonth)->get();

	    	if(count($chkMonthVal) <= 0){

	    		DB::beginTransaction();

		        try { 
		        	foreach($final_ary as $final_info){

		        		ManualFoodAllowance::create($final_info);	
		        	}
		        	
		        	DB::commit();
		        } 
		        catch (\Exception $e){

		            DB::rollback();
		        }
	    	}
	    }
    }

    public function downloadDemo(){
        $pathToFile = public_path('FactoryFoodUpload.csv');
        return response()->download($pathToFile, 'Factory_Food_Upload.csv');
    }
}