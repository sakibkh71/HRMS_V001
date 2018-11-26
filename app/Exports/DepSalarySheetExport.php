<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Salary;
use App\Models\Setting;
use App\Models\PdfInfo;

use Carbon\Carbon;
use App\Services\CommonService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;



class DepSalarySheetExport implements FromView
{
    use Exportable;
    use CommonService;

    protected $branch_id;
    protected $department_id;
    protected $unit_id;
    protected $user_id;
    protected $salary_month;

    public function __construct($branch_id, $department_id, $unit_id, $user_id, $salary_month)
    {
        $this->branch_id = $branch_id;
        $this->department_id = $department_id;
        $this->unit_id = $unit_id;
        $this->user_id = $user_id;
        $this->salary_month = $salary_month;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $user_ids = $this->getUserIds($this->branch_id, $this->department_id, $this->unit_id, $this->user_id, $this->salary_month);

        $salaries = Salary::with('user.details','user.designation','user.unit.department')
            ->whereIn('user_id', $user_ids)
            ->where('salary_month', $this->salary_month)
            ->get();

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

            $salary_reports[] = (object)[
                'user_id'=> $salary->user_id,
                'employee_no' =>  $salary->user->employee_no,
                'employee_designation' => $salary->user->designation->designation_name,
                'full_name' => $salary->user->fullname,
                'joining_date' => ($salary->user->details)?$salary->user->details->joining_date:'',
                'department' => $salary->user->unit->department->department_name,
                'designation' => $salary->user->designation->designation_name,
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


        return view('report.payroll.salarySheetXl', [
            'salary_reports' => $salary_reports,
            'company_details' => Setting::all()
        ]);
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


