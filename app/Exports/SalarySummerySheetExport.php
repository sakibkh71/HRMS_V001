<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Salary;
use App\Models\Setting;
use App\Models\Department;

use Carbon\Carbon;
use App\Services\CommonService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;



class SalarySummerySheetExport implements FromView
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


    public function view(): View
    {
        $get_prev_month = date('Y-m', strtotime($this->salary_month ." -1 month"));

        $user_ids = $this->getUserIds($this->branch_id, $this->department_id, $this->unit_id, $this->user_id, $this->salary_month)->toArray();

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

                $salary_month_first_day = $this->salary_month."-1";
                $salary_month_last_day = date("Y-m-t", strtotime($this->salary_month));

                $salary_month_prev_first_day = $get_prev_month."-1";
                $salary_month_prev_last_day = date("Y-m-t", strtotime($get_prev_month));

                $advance_salary = \DB::table('loans')->select('loan_amount')->where('loan_status', 1)->where('loan_or_advance', 'advance')->whereBetween('updated_at', [$salary_month_first_day, $salary_month_last_day])->whereIn('user_id', $userIdAry)->sum('loan_amount');

                $advance_salary_prev = \DB::table('loans')->select('loan_amount')->where('loan_status', 1)->where('loan_or_advance', 'advance')->whereBetween('updated_at', [$salary_month_prev_first_day, $salary_month_prev_last_day])->whereIn('user_id', $userIdAry)->sum('loan_amount');

                $cash_salary = \DB::table('salaries')->select('salary_in_cash')->where('salary_month', $this->salary_month)->whereIn('user_id', $userIdAry)->sum('salary_in_cash');

                $cash_salary_prev = \DB::table('salaries')->select('salary_in_cash')->where('salary_month', $get_prev_month)->whereIn('user_id', $userIdAry)->sum('salary_in_cash');

                $net_salary = \DB::table('salaries')->select('net_salary')->where('salary_month', $this->salary_month)->whereIn('user_id', $userIdAry)->sum('net_salary');

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
                'pdf_branch_id' => $this->branch_id,
                'pdf_department_id' => $this->department_id,
                'pdf_unit_id' => $this->unit_id,
                'pdf_user_id' => $this->user_id,
                'pdf_salary_month' => $this->salary_month,
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
                'salary_month' => "For The Month Of ".Carbon::parse($this->salary_month)->format('F - Y'),
                'only_month' => Carbon::parse($this->salary_month)->format('F'),
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


        return view('report.payroll.salarySummerySheetXl', [

              'reports' => $final_summery,
              'salary_month' => $this->salary_month,
              'company_details' => Setting::all(),
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
}


