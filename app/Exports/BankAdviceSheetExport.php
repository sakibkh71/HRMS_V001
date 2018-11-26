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



class BankAdviceSheetExport implements FromView
{
    use Exportable;
    use CommonService;

    protected $branch_id;
    protected $department_id;
    protected $unit_id;
    protected $user_id;
    protected $salary_month;
    protected $advice_type;

    public function __construct($branch_id, $department_id, $unit_id, $user_id, $salary_month, $advice_type)
    {
        $this->branch_id = $branch_id;
        $this->department_id = $department_id;
        $this->unit_id = $unit_id;
        $this->user_id = $user_id;
        $this->salary_month = $salary_month;
        $this->advice_type = $advice_type;
    }


    public function view(): View
    {
        $user_ids = $this->getUserIds($this->branch_id, $this->department_id, $this->unit_id, $this->user_id, $this->salary_month);

        $user_accounts = User::with('salaryAccount')->whereIn('id', $user_ids)->get();

        //21 means prev: cash -- now: bank
        //13 means prev: bank -- now: both
        if($this->advice_type == 'bank'){
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $this->salary_month)->whereIn('payment_procedure', [11, 21, 31, 13, 23, 33])->get();
        }
        elseif($this->advice_type == 'cash'){
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $this->salary_month)->whereIn('payment_procedure', [12, 22, 32, 13, 23, 33])->get();
        }
        elseif($this->advice_type == 'both'){
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $this->salary_month)->whereIn('payment_procedure', [13, 23, 33])->get();
        }
        else{
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $this->salary_month)->get();
        }

        $salary_reports = [];
        $advice_reports = [];
        $emp_account = 0;

        foreach($user_salary as $info){

            $bank_account_no = '';
            $bank_account_name = '';
            $bank_branch_name = '';
            $emp_name = '';
            $emp_no = '';

            foreach($user_accounts as $us){

                if($us->id == $info->user_id){
                    if(!empty($us->salaryAccount)){
                        $bank_account_no = $us->salaryAccount->bank_account_no;
                        $bank_account_name = $us->salaryAccount->bank_account_name;
                        $bank_branch_name = $us->salaryAccount->bank_branch_name;
                    }

                    $emp_name = $us->fullname;
                    $emp_no = $us->employee_no;

                    break;
                }else{
                    $bank_account_no = '';
                    $bank_account_name = '';
                    $bank_branch_name = '';
                    $emp_name = '';
                    $emp_no = '';
                }
            }

            $advice_reports[] = (object)[
                'pdf_branch_id' => $this->branch_id,
                'pdf_department_id' => $this->department_id,
                'pdf_unit_id' => $this->unit_id,
                'pdf_user_id' => $this->user_id,
                'pdf_salary_month' => $this->salary_month,
                'user_id'=> $info->user_id,
                'full_name' => $emp_name,
                'employee_no' => $emp_no,
                'salary_in_cash' => round($info->salary_in_cash),
                'salary_month' => "Salary of ".Carbon::parse($this->salary_month)->format('M Y'),
                'salary' => round($info->salary),
                'bank_account_no' => $bank_account_no,
                'bank_account_name' => $bank_account_name,
                'bank_branch_name' => $bank_branch_name,
            ];
        }

        $reports['reports'] = $advice_reports;
        $reports['advice_type'] = $this->advice_type;

        return view('report.payroll.adviceSheetXl', [

              'salary_reports' => $reports,
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


