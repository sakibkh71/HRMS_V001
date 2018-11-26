<?php

namespace App\Http\Controllers\Payroll;

use App\Exports\ReconciliationSheetExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Salary;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryAccount;
use App\Models\PdfInfo;
use App\Models\UserEmployeeTypeMap;
use App\Models\Increment;
use App\Models\Setting;

use App\Services\CommonService;
use App\Services\PermissionService;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class payrollReconciliationController extends Controller
{
	use CommonService;  //PermissionService

	protected $auth;

    public function __construct(Auth $auth){
    	$this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['reportPdf', 'reportXl']]);
        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            if($request->isMethod('post')){
                return $this->generateReconciliationSheet($request, 'ajx');
            }
        }

        // $data['sidebar_hide'] = true;
        $data['title'] = 'Reconciliation';
        $data['departments'] = $this->getDepartments();
        $data['branches'] = $this->getBranches();
        return view('payroll.reconciliation')->with($data);
    }

    public function generateReconciliationSheet($request, $ajxORnot = null){

        if($ajxORnot == 'ajx'){
            $this->validate($request,[
                'salary_month' => 'required',
            ],[
                'salary_month' => 'month',
            ]);

            $branch_id = $request->branch_id;
            $department_id = $request->department_id;
            $unit_id = $request->unit_id;
            $salary_month = $request->salary_month;
        }
        else{
            $branch_id = $request['branch_id'];
            $department_id = $request['department_id'];
            $unit_id = $request['unit_id'];
            $salary_month = $request['salary_month'];
        }

        $user_id = 0;
            
        $get_prev_month = date('Y-m', strtotime($salary_month ." -1 month"));
        $user_ids = $this->getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month)->toArray();
        $all_user_ids = $this->getAllUserIds($branch_id, $department_id, $unit_id, $user_id, $get_prev_month)->toArray();

        $user_salary = Salary::with('user', 'user.employeeTypeMapFirst', 'user.userIncriment')->whereIn('user_id', $user_ids)->where('salary_month', $salary_month)->get(); 
        $user_salary_prev = Salary::with('user')->whereIn('user_id', $all_user_ids)->where('salary_month', $get_prev_month)->get();

        $total_salary_of_prev_month    = $user_salary_prev->sum('net_salary');
        $total_salary_of_current_month = $user_salary->sum('net_salary');
        
        $normal_add_ary = [];
        $normal_less_ary = [];
        $prev_month_user_ids = [];
        $current_month_user_ids = [];
        $slAdd = 0;
        $slLess = 0;

        foreach($user_salary as $us){

            array_push($current_month_user_ids, $us->user_id);
        }

        foreach($user_salary_prev as $info){

            array_push($prev_month_user_ids, $info->user_id);

            foreach($user_salary as $us){

                if($us->user_id == $info->user_id){

                    $empSalary = round($us->net_salary);
                    $empUserId = $us->user_id;
                    $empUserName = $us->user->full_name;
                    $empNo = $us->user->employee_no;
                
                    $prevEmpSalary = round($info->net_salary);
                    $prevEmpUserId = $info->user_id;
                    $prevEmpUserName = $info->user->full_name;
                    $prevEmpNo = $info->user->employee_no;

                    if($prevEmpSalary > $empSalary){
                        $normal_less_ary[$slLess]['amount'] = $prevEmpSalary-$empSalary;
                        $normal_less_ary[$slLess]['status'] = 'less';
                        $normal_less_ary[$slLess]['name'] = $prevEmpUserName;
                        $normal_less_ary[$slLess]['user_id'] = $prevEmpUserId;
                        $normal_less_ary[$slLess]['emp_no'] = $prevEmpNo;
                        $slLess++;
                    }

                    if($prevEmpSalary < $empSalary){
                        $normal_add_ary[$slAdd]['amount'] = $empSalary-$prevEmpSalary;
                        $normal_add_ary[$slAdd]['status'] = 'add';
                        $normal_add_ary[$slAdd]['name'] = $empUserName;
                        $normal_add_ary[$slAdd]['user_id'] = $empUserId;
                        $normal_add_ary[$slAdd]['emp_no'] = $empNo;
                        $slAdd++;
                    }

                    break;
                }
            }
        }

        $may_be_resigned = array_diff($prev_month_user_ids, $current_month_user_ids);
        $may_be_joined = array_diff($current_month_user_ids, $prev_month_user_ids);

        $resigned_ary = [];
        $others_less = [];
        $new_join_ary = [];
        $other_add_ary = [];
        $emp_get_increment_ary = [];
        $slRes = 0;
        $slOthers = 0;
        $slNew = 0;
        $slIncre = 0;
        $slOtherAdd = 0;

        foreach($user_salary_prev as $info){
            if(in_array($info->user_id, $may_be_resigned)){
                
                if($info->user->status == 4){
                    $resigned_ary[$slRes]['amount'] = round($info->net_salary);
                    $resigned_ary[$slRes]['name'] = $info->user->full_name;
                    $resigned_ary[$slRes]['user_id'] = $info->user_id;
                    $resigned_ary[$slRes]['emp_no'] = $info->user->employee_no;
                    $slRes++;
                }
                else{
                    $others_less[$slOthers]['amount'] = round($info->net_salary);
                    $others_less[$slOthers]['name'] = $info->user->full_name;
                    $others_less[$slOthers]['user_id'] = $info->user_id;
                    $others_less[$slOthers]['emp_no'] = $info->user->employee_no;
                    $slOthers++;
                }
            }
        }

        //find out new joined employee
        //and employee get increment
        $timestampSm    = strtotime($salary_month);
        $firstDay = date('Y-m-01', $timestampSm);
        $lastDay  = date('Y-m-t', $timestampSm);

        $salary_month_with_date = date("$salary_month-01");

        foreach($user_salary as $us){

            //find new join
            if(in_array($us->user_id, $may_be_joined)){
                if(!empty($us->user->employeeTypeMapFirst)){
                    if($us->user->employeeTypeMapFirst->from_date >= $salary_month_with_date){
                        //new join
                        $new_join_ary[$slNew]['amount'] = round($us->net_salary);
                        $new_join_ary[$slNew]['name'] = $us->user->full_name;
                        $new_join_ary[$slNew]['user_id'] = $us->user_id;
                        $new_join_ary[$slNew]['emp_no'] = $us->user->employee_no;
                        $slNew++;
                    }
                    else{
                        $other_add_ary[$slOtherAdd]['amount'] = round($us->net_salary);
                        $other_add_ary[$slOtherAdd]['name'] = $us->user->full_name;
                        $other_add_ary[$slOtherAdd]['user_id'] = $us->user_id;
                        $other_add_ary[$slOtherAdd]['emp_no'] = $us->user->employee_no;
                        $slOtherAdd++;
                    }
                }
            }

            //find emp get Increment
            if(count($us->user->userIncriment) > 0){

                $userFinalIncrement = 0;
                foreach($us->user->userIncriment as $usrIncrement){
                    if($usrIncrement->increment_effective_date >= $firstDay && $usrIncrement->increment_effective_date <= $lastDay){
                        $userFinalIncrement = $userFinalIncrement+$usrIncrement->increment_amount;
                    }
                }

                if($userFinalIncrement > 0){
                    $emp_get_increment_ary[$slIncre]['amount'] = round($userFinalIncrement);
                    $emp_get_increment_ary[$slIncre]['name'] = $us->user->full_name;
                    $emp_get_increment_ary[$slIncre]['user_id'] = $us->user_id;
                    $emp_get_increment_ary[$slIncre]['emp_no'] = $us->user->employee_no;
                    $slIncre++;
                }
            }
        }

        //comare and modify normal add  with increment
        $find_indx = 0;
        if(count($normal_add_ary) > 0 && count($emp_get_increment_ary) > 0){
            foreach($normal_add_ary as $addAry){
                foreach($emp_get_increment_ary as $incAry){
                    if($addAry['user_id'] == $incAry['user_id']){
                        if($incAry['amount'] > 0){
                            $normal_add_ary[$find_indx]['amount'] = $normal_add_ary[$find_indx]['amount']-$incAry['amount'];
                        }
                    }
                }

                $find_indx++;
            }
        }

        $final_reconciliation['salary_prv_month'] = round($total_salary_of_prev_month);
        $final_reconciliation['salary_corrent_month'] = round($total_salary_of_current_month);
        $final_reconciliation['normal_add'] = $normal_add_ary;
        $final_reconciliation['other_add'] = $other_add_ary;
        $final_reconciliation['increment_add'] = $emp_get_increment_ary;
        $final_reconciliation['new_join_add'] = $new_join_ary;
        $final_reconciliation['normal_less'] = $normal_less_ary;
        $final_reconciliation['resign_less'] = $resigned_ary;
        $final_reconciliation['other_less'] = $others_less;
        $final_reconciliation['pdf_branch_id'] = $branch_id;
        $final_reconciliation['pdf_department_id'] = $department_id;
        $final_reconciliation['pdf_unit_id'] = $unit_id;
        $final_reconciliation['pdf_salary_month'] = $salary_month;
        
        return $final_reconciliation;
    }

    protected function getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month)
    {
        if($user_id !=0){
            $user_ids = [$user_id];
        }elseif($branch_id !=0 || $department_id !=0 || $unit_id !=0){
            $users = $this->getEmployeeByDepartmentUnitBranch($branch_id, $department_id, $unit_id);
            $user_ids = $users->where('effective_date','<=',Carbon::parse($salary_month)->format('Y-m-t'))->pluck('id');
        }else{
            $users = User::where('status',1)->get();
            $user_ids = $users->where('effective_date','<=',Carbon::parse($salary_month)->format('Y-m-t'))->pluck('id');
        }

        return $user_ids;
    }

    protected function getAllUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month)
    {
        if($user_id !=0){
            $user_ids = [$user_id];
        }elseif($branch_id !=0 || $department_id !=0 || $unit_id !=0){
            $users = $this->getAllEmployeeByDepartmentUnitBranch($branch_id, $department_id, $unit_id);
            $user_ids = $users->where('effective_date','<=',Carbon::parse($salary_month)->format('Y-m-t'))->pluck('id');
        }else{
            $users = User::where('status','<', 20)->get();
            $user_ids = $users->where('effective_date','<=',Carbon::parse($salary_month)->format('Y-m-t'))->pluck('id');
        }

        return $user_ids;
    }

    public function reportPdf(Request $request){

        if($request->salary_month != 0){
            $reports['reports'] = $this->generateReconciliationSheet($request->all());
            $reports['salary_month'] = $request->salary_month;
            $reports['company_details'] = Setting::all();
            
            view()->share('reports',$reports['reports']);
            view()->share('salary_month',$reports['salary_month']);
            view()->share('company_details',$reports['company_details']);

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('report.payroll.reconciliationSheetPDF');
            return $pdf->stream();  

            // return view('report.payroll.reconciliationSheetPDF', $reports);
        }
    }

    public function reportXl(Request $request, $ajxORnot = null){

        if($request->salary_month != 0){

//            $reports['reports'] = $this->generateReconciliationSheet($request->all());
//            $reports['salary_month'] = $request->salary_month;
//            $reports['company_details'] = Setting::all();

            if($ajxORnot == 'ajx'){
                $this->validate($request,[
                    'salary_month' => 'required',
                ],[
                    'salary_month' => 'month',
                ]);

                $branch_id = $request->branch_id;
                $department_id = $request->department_id;
                $unit_id = $request->unit_id;
                $salary_month = $request->salary_month;
            }
            else{
                $branch_id = $request['branch_id'];
                $department_id = $request['department_id'];
                $unit_id = $request['unit_id'];
                $salary_month = $request['salary_month'];
            }

            $user_id = 0;

            // echo gettype($reports['reports'][0]->bank_account_no);
            // dd($reports['reports'][0]->bank_account_no);

            return Excel::download(new ReconciliationSheetExport($branch_id, $department_id, $unit_id, $user_id, $salary_month), 'Reconciliation_Sheet.xlsx');

//            \Excel::create('excel_reconciliation_sheet', function($excel) use ($reports){
//                $excel->sheet('ExportFile', function($sheet) use ($reports){
//
//                    $sheet->setColumnFormat(['C' => '0000']);
//                    $sheet->loadView('report.payroll.reconciliationSheetXl', $reports);
//                });
//            })->export('xls');
        }
    }
}