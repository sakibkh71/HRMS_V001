<?php

namespace App\Http\Controllers\Payroll;

use App\Exports\SalarySummerySheetExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Salary;
use App\Models\Department;
use App\Models\Setting;

use App\Services\CommonService;
use App\Services\PermissionService;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalarySheetSummeryController extends Controller
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
                return $this->generateSalarySheetSummery($request, 'ajx');
            }
        }

        $data['title'] = 'Salary Sheet Summery';
        $data['departments'] = $this->getDepartments();
        $data['branches'] = $this->getBranches();
        return view('payroll.salary_sheet_summery')->with($data);
    }

    public function generateSalarySheetSummery($request, $ajxORnot = null){

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

        $depWithUser = Department::with('designations', 'designations.user')->get();

        $depIndex = 0;
        $totalAdvanceSalary = 0;
        $totalAdvanceSalaryPrev = 0;
        $totalBankSalary = 0;
        $totalBankSalaryPrev = 0;
        $totalCashSalary = 0;
        $totalCashSalaryPrev = 0;
        $totalTotal = 0;
        $totalTotalPrev = 0;
        
        foreach($depWithUser as $info){

            $usrCounter = 0;
            $userIdAry = [];
            $advance_salary = 0;
            $advance_salary_prev = 0;
            $cash_salary = 0;
            $cash_salary_prev = 0;
            $net_salary = 0;
            $net_salary_prev = 0;
            $bank_salary = 0;
            $bank_salary_prev = 0;
            $total = 0;
            $total_prev = 0;

            foreach($info->designations as $val){
                foreach($val->user as $usr){
                    array_push($userIdAry, $usr->id);
                }
            }

            if(count($userIdAry) > 0){

                $salary_month_first_day = $salary_month."-1";
                $salary_month_last_day = date("Y-m-t", strtotime($salary_month));

                $salary_month_prev_first_day = $get_prev_month."-1";
                $salary_month_prev_last_day = date("Y-m-t", strtotime($get_prev_month));

                $advance_salary = \DB::table('loans')->select('loan_amount')->where('loan_status', 1)->where('loan_or_advance', 'advance')->whereBetween('updated_at', [$salary_month_first_day, $salary_month_last_day])->whereIn('user_id', $userIdAry)->sum('loan_amount');

                $advance_salary_prev = \DB::table('loans')->select('loan_amount')->where('loan_status', 1)->where('loan_or_advance', 'advance')->whereBetween('updated_at', [$salary_month_prev_first_day, $salary_month_prev_last_day])->whereIn('user_id', $userIdAry)->sum('loan_amount');

                $cash_salary = \DB::table('salaries')->select('salary_in_cash')->where('salary_month', $salary_month)->whereIn('user_id', $userIdAry)->sum('salary_in_cash');

                $cash_salary_prev = \DB::table('salaries')->select('salary_in_cash')->where('salary_month', $get_prev_month)->whereIn('user_id', $userIdAry)->sum('salary_in_cash');

                $net_salary = \DB::table('salaries')->select('net_salary')->where('salary_month', $salary_month)->whereIn('user_id', $userIdAry)->sum('net_salary');

                $net_salary_prev = \DB::table('salaries')->select('net_salary')->where('salary_month', $get_prev_month)->whereIn('user_id', $userIdAry)->sum('net_salary');

                $bank_salary = $net_salary - $cash_salary;
                $bank_salary_prev = $net_salary_prev - $cash_salary_prev;
                $total = $bank_salary + $cash_salary + $advance_salary;
                $total_prev = $bank_salary_prev + $cash_salary_prev + $advance_salary_prev;
            }

            $depWithUserAry[$depIndex]['advance_salary'] = $advance_salary;
            $depWithUserAry[$depIndex]['advance_salary_prev'] = $advance_salary_prev;
            $depWithUserAry[$depIndex]['dep_name'] = $info->department_name;
            $depWithUserAry[$depIndex]['cash_salary'] = $cash_salary;
            $depWithUserAry[$depIndex]['cash_salary_prev'] = $cash_salary_prev;
            $depWithUserAry[$depIndex]['net_salary'] = $net_salary;
            $depWithUserAry[$depIndex]['net_salary_prev'] = $net_salary_prev;
            $depWithUserAry[$depIndex]['bank_salary'] = $bank_salary;
            $depWithUserAry[$depIndex]['bank_salary_prev'] = $bank_salary_prev;
            $depWithUserAry[$depIndex]['total'] = $total;
            $depWithUserAry[$depIndex]['total_prev'] = $total_prev;

            $totalAdvanceSalary = $totalAdvanceSalary + $advance_salary;
            $totalAdvanceSalaryPrev = $totalAdvanceSalaryPrev + $advance_salary_prev;
            $totalBankSalary = $totalBankSalary + $bank_salary;
            $totalBankSalaryPrev = $totalBankSalaryPrev + $bank_salary_prev;
            $totalCashSalary = $totalCashSalary + $cash_salary;
            $totalCashSalaryPrev = $totalCashSalaryPrev + $cash_salary_prev;
            $totalTotal = $totalTotal + $total;
            $totalTotalPrev = $totalTotalPrev + $total_prev;

            $depIndex++; 
        }

        $final_summery = [];

        foreach($depWithUserAry as $dep){
            
            $final_summery[] = (object)[
                'pdf_branch_id' => $branch_id,
                'pdf_department_id' => $department_id,
                'pdf_unit_id' => $unit_id,
                'pdf_user_id' => $user_id,
                'pdf_salary_month' => $salary_month,
                'dep_name' => $dep['dep_name'],
                'advance_salary' => $dep['advance_salary'],
                'advance_salary_prev' => $dep['advance_salary_prev'],
                'cash_salary' => $dep['cash_salary'],
                'cash_salary_prev' => $dep['cash_salary_prev'],
                'net_salary' => $dep['net_salary'],
                'net_salary_prev' => $dep['net_salary_prev'],
                'bank_salary' => $dep['bank_salary'],
                'bank_salary_prev' => $dep['bank_salary_prev'],
                'total' => $dep['total'],
                'total_prev' => $dep['total_prev'],
                'salary_month' => "For The Month Of ".Carbon::parse($salary_month)->format('F - Y'),
                'only_month' => Carbon::parse($salary_month)->format('F'),
                'only_month_prev' => Carbon::parse($get_prev_month)->format('F'),
                'total_advance_salary' => $totalAdvanceSalary,
                'total_advance_salary_prev' => $totalAdvanceSalaryPrev,
                'total_bank' => $totalBankSalary, 
                'total_bank_prev' => $totalBankSalaryPrev,
                'total_cash' => $totalCashSalary, 
                'total_cash_prev' => $totalCashSalaryPrev, 
                'total_total' => $totalTotal, 
                'total_total_prev' => $totalTotalPrev, 
            ];
        }

        return $final_summery;
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

    public function reportPdf(Request $request){

        if($request->salary_month != 0){
            $reports['reports'] = $this->generateSalarySheetSummery($request->all());
            $reports['salary_month'] = $request->salary_month;
            $reports['company_details'] = Setting::all();

            view()->share('reports',$reports['reports']);
            view()->share('salary_month',$reports['salary_month']);
            view()->share('company_details',$reports['company_details']);

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('report.payroll.salarySheetSummeryPDF');
            return $pdf->stream();  

            // return view('report.payroll.journalSheetPDF', $reports);
        }
    }

    public function reportXl(Request $request, $ajxORnot = null){

        if($request->salary_month != 0){

//            $reports['reports'] = $this->generateSalarySheetSummery($request->all());
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

            return Excel::download(new SalarySummerySheetExport($branch_id, $department_id, $unit_id, $user_id, $salary_month), 'Salary_Summery_Sheet.xlsx');

//            \Excel::create('excel_salary_summery_sheet', function($excel) use ($reports){
//                $excel->sheet('ExportFile', function($sheet) use ($reports){
//
//                    $sheet->setColumnFormat(['C' => '0000']);
//                    $sheet->loadView('report.payroll.salarySummerySheetXl', $reports);
//                });
//            })->export('xls');
        }
    }

    
}