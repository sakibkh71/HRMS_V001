<?php

namespace App\Http\Controllers\Payroll;

use App\Models\User;
use App\Models\Salary;
use App\Models\Setting;
use App\Models\PdfInfo;

use App\Services\CommonService;

use App\Exports\DepSalarySheetExport;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

class payrollReportController extends Controller
{
	use CommonService;

	protected $auth;

    public function __construct(Auth $auth){
    	$this->middleware('auth:hrms');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });
    }

    public function depSalarySheet(Request $request){

        $branch_id = $request->branch_id;
        $department_id = $request->department_id;
        $unit_id = $request->unit_id;
        $user_id = $request->user_id;
        $salary_month = $request->salary_month;

        //plain Gross Calculation
        $explodeSalaryMonth = explode('-', $salary_month);
        $daysOfSalaryMonth = cal_days_in_month(CAL_GREGORIAN, $explodeSalaryMonth[1],$explodeSalaryMonth[0]);

        $company_address = Setting::all();

        $user_ids = $this->getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month);

        $salaries = Salary::with('user.details','user.designation','user.unit.department')
                    ->whereIn('user_id', $user_ids)
                    ->where('salary_month', $salary_month)
                    ->get();

        // $salaries = Salary::with('user.details','user.designation.department','user.unit.department')
        //             ->whereIn('user_id', $user_ids)
        //             ->where('salary_month', $salary_month)
        //             ->join('users', 'salaries.user_id', '=', 'users.id')
        //             ->join('designations', 'users.designation_id', '=', 'designations.id')
        //             ->orderBy('designations.department_id')
        //             ->get();

        // foreach($salaries as $info){
        //     echo 'user_id => '.$info->user_id;

        //     echo "---------- Department_id =>".$info->user->designation->department_id."---".$info->user->designation->department->department_name."<br/>"; 
        // }

        // dd($salaries[0], $user_ids, $salary_month);

        $salary_reports = [];
        foreach($salaries as $salary)
        {
            //calculation for totally cash salary
            $totally_cash_basic = 0;
            $totally_cash_gross = 0;

            if($salary->basic_salary < 1){
                $totally_cash_basic = (round($salary->net_salary)+$salary->total_deduction)-round($salary->gross_salary);
                $totally_cash_gross = $totally_cash_basic + round($salary->gross_salary);
            }

            //plain gross salary calculation
            $plain_gross_salary = round($daysOfSalaryMonth * $salary->perday_salary);

            $salary_reports[] = (object)[
                'user_id'=> $salary->user_id,
                'employee_no' =>  $salary->user->employee_no,
                'employee_designation' => $salary->user->designation->designation_name,
                'full_name' => $salary->user->fullname,
                'joining_date' => ($salary->user->details)?$salary->user->details->joining_date:'',
                'department' => $salary->user->unit->department->department_name,
                'designation' => $salary->user->designation->designation_name,
                'fixedSalary' => $plain_gross_salary,
                'basic_salary' => (round($salary->basic_salary) < 1)?$totally_cash_basic:round($salary->basic_salary),
                'salary_in_cash' => round($salary->salary_in_cash),
                'salary_month' => $salary->salary_month,
                'salary_month_format' => Carbon::parse($salary->salary_month)->format('M Y'),
                'salary_pay_type' => $salary->salary_pay_type,
                'salary_days' => $salary->salary_days,
                'overtime_hour' => $salary->overtime_hour,
                'overtime_amount' => $salary->overtime_amount,
                'attendances' => unserialize($salary->attendance_info),
                'allowances'=> unserialize($salary->allowance_info),
                'total_allowance' => $salary->total_allowance,
                'deductions'=> unserialize($salary->deduction_info),
                'total_deduction' => $salary->total_deduction,
                'work_hour' => $salary->work_hour,
                'perhour_salary' => round($salary->perhour_salary),
                'perday_salary' => round($salary->perday_salary),
                'salary' => round($salary->salary),
                'gross_salary' => (round($salary->basic_salary) < 1)?$totally_cash_gross:round($salary->gross_salary),
                'net_salary' => round($salary->net_salary),
                'total_salary' => round($salary->total_salary),
                'remarks' => $salary->remarks
            ];
        }

        $signatures = PdfInfo::where('report_pdf_id', 1)->first();

        view()->share('department_id',$department_id);
        view()->share('salary_reports',$salary_reports);
        view()->share('company_details',$company_address);
        view()->share('signatures',unserialize($signatures->signatures));

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('report.payroll.salarySheetPDF');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();  
        // return view('report.payroll.salarySheetPDF');
    }

    public function depSalarySheetExl(Request $request){

        $branch_id = $request->branch_id;
        $department_id = $request->department_id;
        $unit_id = $request->unit_id;
        $user_id = $request->user_id;
        $salary_month = $request->salary_month;

//        $company_address = Setting::all();
//
//        $user_ids = $this->getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month);
//
//        $salaries = Salary::with('user.details','user.designation','user.unit.department')
//                    ->whereIn('user_id', $user_ids)
//                    ->where('salary_month', $salary_month)
//                    ->get();
//
//        $salary_reports = [];
//        foreach($salaries as $salary)
//        {
//            //calculation for totally cash salary
//            $totally_cash_basic = 0;
//            $totally_cash_gross = 0;
//
//            if($salary->basic_salary < 1){
//                $totally_cash_basic = (round($salary->net_salary)+$salary->total_deduction)-round($salary->gross_salary);
//                $totally_cash_gross = $totally_cash_basic + round($salary->gross_salary);
//            }
//
//            $salary_reports[] = (object)[
//                'user_id'=> $salary->user_id,
//                'employee_no' =>  $salary->user->employee_no,
//                'employee_designation' => $salary->user->designation->designation_name,
//                'full_name' => $salary->user->fullname,
//                'joining_date' => ($salary->user->details)?$salary->user->details->joining_date:'',
//                'department' => $salary->user->unit->department->department_name,
//                'designation' => $salary->user->designation->designation_name,
//                'basic_salary' => (round($salary->basic_salary) < 1)?$totally_cash_basic:round($salary->basic_salary),
//                'salary_in_cash' => round($salary->salary_in_cash),
//                'salary_month' => $salary->salary_month,
//                'salary_month_format' => Carbon::parse($salary->salary_month)->format('M Y'),
//                'salary_pay_type' => $salary->salary_pay_type,
//                'salary_days' => $salary->salary_days,
//                'overtime_hour' => $salary->overtime_hour,
//                'overtime_amount' => $salary->overtime_amount,
//                'attendances' => unserialize($salary->attendance_info),
//                'allowances'=> unserialize($salary->allowance_info),
//                'total_allowance' => $salary->total_allowance,
//                'deductions'=> unserialize($salary->deduction_info),
//                'total_deduction' => $salary->total_deduction,
//                'work_hour' => $salary->work_hour,
//                'perhour_salary' => round($salary->perhour_salary),
//                'perday_salary' => round($salary->perday_salary),
//                'salary' => round($salary->salary),
//                'gross_salary' => (round($salary->basic_salary) < 1)?$totally_cash_gross:round($salary->gross_salary),
//                'net_salary' => round($salary->net_salary),
//                'total_salary' => round($salary->total_salary),
//                'remarks' => $salary->remarks
//            ];
//        }
//
//        $data['salary_reports'] = $salary_reports;
//        $data['company_details'] = $company_address;

        return Excel::download(new DepSalarySheetExport($branch_id, $department_id, $unit_id, $user_id, $salary_month), 'SalarySheet.xlsx');

    }

    protected function getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month)
    {
        if($user_id !=0){
            $user_ids = [$user_id];
        }elseif($branch_id !=0 || $department_id !=0 || $unit_id !=0){
            $users = $this->getEmployeeByDepartmentUnitBranch($branch_id, $department_id, $unit_id);
            $user_ids = $users->pluck('id');
        }else{
            $users = User::where('status',1)->get();
            $user_ids = $users->pluck('id');
        }
        return $user_ids;
    }

}
