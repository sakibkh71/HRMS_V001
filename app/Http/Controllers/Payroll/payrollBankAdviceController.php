<?php

namespace App\Http\Controllers\Payroll;

use App\Exports\BankAdviceSheetExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Salary;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryAccount;
use App\Models\PdfInfo;

use App\Services\CommonService;
use App\Services\PermissionService;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class payrollBankAdviceController extends Controller
{
	use CommonService, PermissionService;

	protected $auth;

    public function __construct(Auth $auth){
    	$this->middleware('auth:hrms');
        $this->middleware('CheckPermissions', ['except' => ['advicePdf', 'saveCoverLetter', 'getCoverLetter', 'adviceXl']]);
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
                return $this->generateAdviceSheet($request, 'ajx');
            }
        }

        // $data['sidebar_hide'] = true;
        $data['title'] = 'Bank/Cash Advice';
        $data['departments'] = $this->getDepartments();
        $data['branches'] = $this->getBranches();
        return view('payroll.bankAdvice')->with($data);
    }

    public function generateAdviceSheet($request, $ajxORnot = null){

        if($ajxORnot == 'ajx'){
            $this->validate($request,[
                'salary_month' => 'required',
            ],[
                'salary_month' => 'month',
            ]);

            $branch_id = $request->branch_id;
            $department_id = $request->department_id;
            $unit_id = $request->unit_id;
            $user_id = $request->user_id;
            $salary_month = $request->salary_month;
            $advice_type = $request->advice_type;
        }
        else{
            $branch_id = $request['branch_id'];
            $department_id = $request['department_id'];
            $unit_id = $request['unit_id'];
            $user_id = $request['user_id'];
            $salary_month = $request['salary_month'];
            $advice_type = $request['advice_type'];
        }
            

        $user_ids = $this->getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month);
        
        $user_accounts = User::with('salaryAccount')->whereIn('id', $user_ids)->get();

        //21 means prev: cash -- now: bank 
        //13 means prev: bank -- now: both 
        if($advice_type == 'bank'){
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $salary_month)->whereIn('payment_procedure', [11, 21, 31, 13, 23, 33])->get();
        }
        elseif($advice_type == 'cash'){
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $salary_month)->whereIn('payment_procedure', [12, 22, 32, 13, 23, 33])->get();
        }
        elseif($advice_type == 'both'){
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $salary_month)->whereIn('payment_procedure', [13, 23, 33])->get();
        }
        else{
            $user_salary = Salary::whereIn('user_id', $user_ids)->where('salary_month', $salary_month)->get();   
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
                'pdf_branch_id' => $branch_id,
                'pdf_department_id' => $department_id,
                'pdf_unit_id' => $unit_id,
                'pdf_user_id' => $user_id,
                'pdf_salary_month' => $salary_month,
                'user_id'=> $info->user_id,
                'full_name' => $emp_name,
                'employee_no' => $emp_no,
                'salary_in_cash' => round($info->salary_in_cash),
                'salary_month' => "Salary of ".Carbon::parse($salary_month)->format('M Y'),
                'salary' => round($info->salary),
                'bank_account_no' => $bank_account_no,
                'bank_account_name' => $bank_account_name,
                'bank_branch_name' => $bank_branch_name,
            ];
        }

        return $advice_reports;
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

    public function advicePdf(Request $request){

        if($request->salary_month != 0){
            $reports['reports'] = $this->generateAdviceSheet($request->all());
            $reports['advice_type'] = $request->advice_type;

            $datas = PdfInfo::where('report_pdf_id', 2)->first();
            
            view()->share('salary_reports',$reports);
            view()->share('bank_letter_head',$datas->cover_head_text);

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadView('report.payroll.adviceSheetPDF');
            return $pdf->stream();  

            // return view('report.payroll.adviceSheetPDF');
        }
    }

    public function adviceXl(Request $request, $ajxORnot = null){

        if($request->salary_month != 0){

            if($ajxORnot == 'ajx'){
                $this->validate($request,[
                    'salary_month' => 'required',
                ],[
                    'salary_month' => 'month',
                ]);

                $branch_id = $request->branch_id;
                $department_id = $request->department_id;
                $unit_id = $request->unit_id;
                $user_id = $request->user_id;
                $salary_month = $request->salary_month;
                $advice_type = $request->advice_type;
            }
            else{
                $branch_id = $request['branch_id'];
                $department_id = $request['department_id'];
                $unit_id = $request['unit_id'];
                $user_id = $request['user_id'];
                $salary_month = $request['salary_month'];
                $advice_type = $request['advice_type'];
            }

//            $reports['reports'] = $this->generateAdviceSheet($request->all());
//            $reports['advice_type'] = $request->advice_type;
//
//            $data['salary_reports'] = $reports;


            return Excel::download(new BankAdviceSheetExport($branch_id, $department_id, $unit_id, $user_id, $salary_month, $advice_type), 'Bank_Cash_Advice_Sheet.xlsx');

//            \Excel::create('excel_bank_cash_advice', function($excel) use ($data){
//                $excel->sheet('ExportFile', function($sheet) use ($data){
//
//                    $sheet->setColumnFormat(['C' => '0000']);
//                    $sheet->loadView('report.payroll.adviceSheetXl', $data);
//                });
//            })->export('xls');
        }
    }

    public function getCoverLetter(){

        $datas = PdfInfo::where('report_pdf_id', 2)->first();

        return $datas->cover_head_text;
    }

    public function saveCoverLetter(Request $request){

        $chk = PdfInfo::where('report_pdf_id', 2)->first();

        if(!empty($chk) > 0){
            PdfInfo::where('report_pdf_id', 2)->update([
                    'cover_head_text' => $request->ckeditorId,
                ]);
        }
        else{
            $sav = new PdfInfo;
            $sav->report_pdf_id = 2;
            $sav->cover_head_text = $request->ckeditorId;
            $sav->save();
        }

        $data['title'] = 'success';
        $data['message'] = "Data updated successfully!";

        return $data;
    }
}