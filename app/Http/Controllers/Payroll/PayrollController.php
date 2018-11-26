<?php

namespace App\Http\Controllers\Payroll;

use App\Models\User;
use App\Models\Salary;
use App\Models\EmployeeSalary;
use App\Models\AttendanceTimesheet;
use App\Models\BasicSalaryInfo;
use App\Models\ManualFoodAllowance;
use App\Models\LoanDetails;
use App\Models\Loan;

use App\Services\CommonService;
use App\Jobs\DebitProvidentFundToSalaryGenerate;
use App\Jobs\LoanInstallmentUpdateToSalaryGenerate;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{
	use CommonService;

	protected $auth;

    public function __construct(Auth $auth, AttendanceTimesheet $attendanceTimesheet){
    	$this->middleware('auth:hrms');

        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('hrms')->user();
            view()->share('auth',$this->auth);
            return $next($request);
        });

        $this->attendanceTimesheet = $attendanceTimesheet;

    }


    public function index(Request $request)
    {
    	if($request->ajax()){
    		if($request->isMethod('post')){
	    		return $this->generateSalary($request);
    		}else{
    			return $this->getEmployeeByDepartmentUnitBranch($request->segment(3), $request->segment(4), $request->segment(5));
    		}
    	}

    	$data['sidebar_hide'] = true;
    	$data['departments'] = $this->getDepartments();
    	$data['branches'] = $this->getBranches();
    	return view('payroll.payroll')->with($data);
    }


    public function addAllSalary(Request $request)
    {   
        $userInput = $request->all();

        try{
            foreach($userInput as $valuess){

                $rDays = $valuess['days'];
                $rPaymentDays = $valuess['payment_days'];
                $rBasicSalary = floatval(str_replace(',','',$valuess['basic_salary']));
                $rTotalAllowance = sprintf ("%.2f", floatval(str_replace(',','',$valuess['total_allowance'])));
                $rTotalDeduction = sprintf ("%.2f", floatval(str_replace(',','',$valuess['total_deduction'])));
                $salaryInCash = floatval(str_replace(',','',$valuess['salary_in_cash']));

                $basicSalaryCalculation = sprintf ("%.2f", ($rBasicSalary / $rDays) * $rPaymentDays);
                $salaryInCashCalculation = sprintf ("%.2f", ($salaryInCash / $rDays) * $rPaymentDays);
                $grossSalaryCalculation = ($basicSalaryCalculation + $rTotalAllowance);

                $netSalaryCalculation = round($grossSalaryCalculation + $salaryInCashCalculation) - $rTotalDeduction;
                $bankSalary = $netSalaryCalculation - round($salaryInCashCalculation);

                if($rBasicSalary < 1){
                    //if cash salary is main salary there is no basic or bank salary
                    //then cash salary will be the net salary
                    $salaryInCashCalculation = $netSalaryCalculation;
                }

                $allowanceAry = [];
                $rAmount = 0;

                $payment_procedure = $valuess['payment_procedure'];
                $payment_procedure_default = $valuess['payment_procedure_default'];

                $current_payment_procedure = "$payment_procedure_default$payment_procedure";


                if($current_payment_procedure == "11" || $current_payment_procedure == "21" || $current_payment_procedure == "31"){
                    //Bank
                    $bankSalary = $netSalaryCalculation;
                    $salaryInCash = 00;
                }
                elseif($current_payment_procedure == "12" || $current_payment_procedure == "22" || $current_payment_procedure == "32"){
                    //Cash
                    $bankSalary = 00;
                    $salaryInCash = $netSalaryCalculation;
                }

                if(count($valuess['allowances']) > 0){
                    foreach($valuess['allowances'] as $infos){
                    
                        if($infos['amount_type'] == "percent"){

                            $rAmount = (floatval(str_replace(',','',$valuess['gross_salary'])) * $infos['percent'])/100;
                        }
                        else{
                            $rAmount = $infos['amount'];
                        }

                        //is_bonus not effected by Absent
                        $rAmount = empty($infos['is_bonus'])?(($rAmount/$rDays)*$rPaymentDays):$rAmount;

                        $infoAry['name'] = $infos['name'];
                        $infoAry['amount'] = sprintf ("%.2f", $rAmount);
                        $infoAry['type'] = empty($infos['is_bonus'])?'allowance':'bonus';

                        array_push($allowanceAry, $infoAry);
                    }
                }

                $deductAry = [];
                $dAmount = 0;
                $removeAdvanceLoan = 0;

                if(count($valuess['deductions']) > 0){
                    foreach($valuess['deductions'] as $infos){
                        // var_dump($infos['name']);
                        if($infos['amount_type'] == "percent"){

                            $dAmount = (floatval(str_replace(',','',$valuess['gross_salary'])) * $infos['percent'])/100;
                        }
                        else{
                            $dAmount = $infos['amount'];
                        }

                        if($infos['name'] != 'Advance-remove'){
                            $infoAry['name'] = $infos['name'];
                            $infoAry['amount'] = sprintf ("%.2f", $dAmount);

                            array_push($deductAry, $infoAry);
                        }  

                        // if($infos['name'] == 'Advance-remove'){
                        //     $removeAdvanceLoan = $removeAdvanceLoan + $infos['amount'];
                        // }  
                    }
                }

                $advance_status = '';
                $advance_deduct_amount = '';

                if(count($valuess['deductions']) > 0){
                    foreach($valuess['deductions'] as $info){

                        if(strpos($info['name'], 'dvance')){
                            $advance_status = 'advance';
                            $advance_deduct_amount = $info['amount']; 
                        }

                        if(strpos($info['name'], 'dvance-remov')){
                            $advance_status = 'advance-remove';
                            $advance_deduct_amount = $info['amount']; 
                        }
                    }
                }

                if($advance_status == 'advance'){
                    foreach($valuess['deductions'] as $info){
                        if(strpos($info['name'], 'dvance')){

                            $advance_amount = $info['amount']; 
                            $loan_id = $info['loan_id']; 
                        }
                    }

                    $chk = LoanDetails::where('user_id', $valuess['user_id'])->where('loan_id', $loan_id)->where('salary_month', $valuess['salary_month'])->get();

                    if(count($chk) == 0){

                        $upd = Loan::find($loan_id);
                        $loan_amount = $upd->loan_amount;
                        $upd->loan_complete_duration = $upd->loan_complete_duration + 1;

                        if($upd->loan_duration == $upd->loan_complete_duration)
                        {
                            $upd->loan_status = 0;
                        }

                        $upd->save();

                        $loanDetailsSum = LoanDetails::where('loan_id', $loan_id)->sum('amount');

                        if(($loan_amount - $loanDetailsSum) < $advance_amount){
                            $advance_amount = $loan_amount - $loanDetailsSum;
                        }
                        
                        $savv = new LoanDetails;
                        $savv->user_id = $valuess['user_id'];
                        $savv->loan_id = $loan_id;
                        $savv->amount = $advance_amount;
                        $savv->salary_month = $valuess['salary_month'];
                        $savv->date = Carbon::now()->format('Y-m-d');
                        $savv->created_by = $this->auth->id;
                        $savv->save();
                    }   
                }

                if($advance_status == 'advance-remove'){
                    foreach($valuess['deductions'] as $info){
                        if(strpos($info['name'], 'dvance-remo')){

                            $advance_amount = $info['amount']; 
                            $loan_id = $info['loan_id']; 
                        }
                    }

                    $loanDetaiDel = LoanDetails::where('user_id', $valuess['user_id'])->where('loan_id', $loan_id)->where('salary_month', $valuess['salary_month'])->first();

                    if(count($loanDetaiDel) > 0){
                        $loanDetaiDel->delete();
                        
                        $upd = Loan::find($loan_id);
                        $upd->loan_complete_duration = $upd->loan_complete_duration - 1;
                        $upd->save();
                    }
                }

                // $finalTotalDecuct = $valuess['total_deduction'] - $removeAdvanceLoan;
                $finalTotalDecuct = $valuess['total_deduction'];

                // return $valuess['total_deduction'];

                $salaries = [
                    'user_id' => $valuess['user_id'],
                    // 'basic_salary' => floatval(str_replace(',','',$valuess['basic_salary'])),
                    'basic_salary' => $basicSalaryCalculation,
                    'salary_in_cash' => round($salaryInCashCalculation),
                    // 'salary_in_cash' => $salaryInCash,
                    'salary_month' => $valuess['salary_month'],
                    'salary_days' => $valuess['payment_days'],
                    'salary_pay_type' => $valuess['salary_pay_type'],
                    'work_hour' => $valuess['work_hour'],
                    'overtime_hour' => $valuess['overtime_hour'],
                    'overtime_amount' => $valuess['overtime_amount'],
                    'attendance_info' => serialize($valuess['attendances']),
                    'allowance_info' => serialize($allowanceAry),
                    'deduction_info' => serialize($deductAry),
                    'total_allowance' => floatval(str_replace(',','',$valuess['total_allowance'])),
                    // 'total_deduction' => floatval(str_replace(',','',$valuess['total_deduction'])),
                    'total_deduction' => floatval(str_replace(',','',$finalTotalDecuct)),
                    'perhour_salary' => floatval(str_replace(',','',$valuess['perhour_salary'])),
                    'perday_salary' => floatval(str_replace(',','',$valuess['perday_salary'])),
                    'salary' => $bankSalary,
                    // 'gross_salary' => floatval(str_replace(',','',$valuess['gross_salary'])),
                    'gross_salary' => $grossSalaryCalculation,
                    // 'net_salary' => floatval(str_replace(',','',$valuess['net_salary'])),
                    'net_salary' => $netSalaryCalculation,
                    'total_salary' => floatval(str_replace(',','',$valuess['total_salary'])),
                    'remarks' => $valuess['remarks'],
                    'payment_procedure' => $current_payment_procedure,
                ];

                $salarys = Salary::where('user_id', $valuess['user_id'])
                        ->where('salary_month', $valuess['salary_month'])
                        ->get();

                DB::beginTransaction();

                if($valuess['salary_pay_type'] == 'full')
                {
                    $salary_partial = $salarys->where('salary_pay_type', 'partial')->count();
                    if($salary_partial > 0){
                        $data['status'] = 'warning';
                        $data['statusType'] = 'OK';
                        $data['code'] = 200;
                        $data['title'] = 'Warning!';
                        $data['message'] = "You can't provide full month salary for this employee. Cause this month he/she already taken partial salary.";
                        return response()->json($data,200);
                    }

                    $salary_full = $salarys->where('salary_pay_type', 'full')->count();
                
                    if($salary_full <= 0){
                        $salaries['created_by'] = $this->auth->id;
                        $salaries['created_at'] = Carbon::now()->format('Y-m-d h:i:s');
                        Salary::insert($salaries);
                    }
                    else
                    {
                        $salaries['updated_by'] = $this->auth->id;
                        $salaries['updated_at'] = Carbon::now()->format('Y-m-d h:i:s');

                        Salary::where('user_id', $valuess['user_id'])
                            ->where('salary_month', $valuess['salary_month'])
                            ->where('salary_pay_type', $valuess['salary_pay_type'])
                            ->update($salaries);
                    }

                    // dispatch(new DebitProvidentFundToSalaryGenerate($salaries));
                    // dispatch(new LoanInstallmentUpdateToSalaryGenerate($salaries));
                }
                elseif($valuess['salary_pay_type'] == 'partial')
                {
                    $salary = $salarys->where('salary_pay_type', 'full')->count();
                    // dd($salary);
                    if($salary > 0){
                        $data['status'] = 'warning';
                        $data['statusType'] = 'OK';
                        $data['code'] = 200;
                        $data['title'] = 'Warning!';
                        $data['message'] = 'This employee full salary already added.';
                        return response()->json($data,200);
                    }

                    $salary_pay_days = $valuess['payment_days'];
                    $already_pay_day = $salarys->where('salary_pay_type', 'partial')->sum('salary_days');
                    $present_month_days = Carbon::parse($valuess['salary_month'])->daysInMonth;
                
                    if($present_month_days > $already_pay_day ){
                        $salary_due_days = $present_month_days - $already_pay_day;
                        if($salary_due_days < $salary_pay_days){
                            $data['status'] = 'warning';
                            $data['statusType'] = 'OK';
                            $data['code'] = 200;
                            $data['title'] = 'Warning!';
                            $data['message'] = 'This employee already taken '.$already_pay_day.' days salary.';
                            return response()->json($data,200);
                        }
                    }else{
                        $data['status'] = 'warning';
                        $data['statusType'] = 'OK';
                        $data['code'] = 200;
                        $data['title'] = 'Warning!';
                        $data['message'] = 'This employee full salary already added.';
                        return response()->json($data,200);
                    }

                    $salary = $salarys->where('salary_pay_type', 'full')->count();
                    $salaries['created_by'] = $this->auth->id;
                    $salaries['created_at'] = Carbon::now()->format('Y-m-d h:i:s');
                    Salary::insert($salaries);
                }

                DB::commit();
            }

            $data['status'] = 'success';
            $data['statusType'] = 'OK';
            $data['code'] = 200;
            $data['title'] = 'Success!';
            $data['message'] = 'Salary Successfully Added!';
            return response()->json($data,200);

        }catch(\Exception $e){
            DB::rollback();
            if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Salary Not Added!';
                return response()->json($data,500);
            }
        }
    }

    public function addSalary(Request $request)
    {   
        
    	try{
            $rDays = $request->days;
            $rPaymentDays = $request->payment_days;
            $rBasicSalary = floatval(str_replace(',','',$request->basic_salary));
            $rTotalAllowance = sprintf ("%.2f", floatval(str_replace(',','',$request->total_allowance)));
            $rTotalDeduction = sprintf ("%.2f", floatval(str_replace(',','',$request->total_deduction)));
            $salaryInCash = floatval(str_replace(',','',$request->salary_in_cash));

            $basicSalaryCalculation = sprintf ("%.2f", ($rBasicSalary / $rDays) * $rPaymentDays);
            $salaryInCashCalculation = sprintf ("%.2f", ($salaryInCash / $rDays) * $rPaymentDays);
            $grossSalaryCalculation = ($basicSalaryCalculation + $rTotalAllowance);

            $netSalaryCalculation = round($grossSalaryCalculation + $salaryInCashCalculation) - $rTotalDeduction;
            $bankSalary = $netSalaryCalculation - round($salaryInCashCalculation);

            if($rBasicSalary < 1){
                //if cash salary is main salary there is no basic or bank salary
                //then cash salary will be the net salary
                $salaryInCashCalculation = $netSalaryCalculation;
            }

            $allowanceAry = [];
            $rAmount = 0;

            $payment_procedure = $request->payment_procedure;
            $payment_procedure_default = $request->payment_procedure_default;

            $current_payment_procedure = "$payment_procedure_default$payment_procedure";


            if($current_payment_procedure == "11" || $current_payment_procedure == "21" || $current_payment_procedure == "31"){
                //Bank
                $bankSalary = $netSalaryCalculation;
                $salaryInCash = 00;
            }
            elseif($current_payment_procedure == "12" || $current_payment_procedure == "22" || $current_payment_procedure == "32"){
                //Cash
                $bankSalary = 00;
                $salaryInCash = $netSalaryCalculation;
            }

            if(count($request->allowances) > 0){
                foreach($request->allowances as $infos){
                
                    if($infos['amount_type'] == "percent"){

                        $rAmount = (floatval(str_replace(',','',$request->gross_salary)) * $infos['percent'])/100;
                    }
                    else{
                        $rAmount = $infos['amount'];
                    }

                    //is_bonus not effected by Absent
                    $rAmount = empty($infos['is_bonus'])?(($rAmount/$rDays)*$rPaymentDays):$rAmount;

                    $infoAry['name'] = $infos['name'];
                    $infoAry['amount'] = sprintf ("%.2f", $rAmount);
                    $infoAry['type'] = empty($infos['is_bonus'])?'allowance':'bonus';

                    array_push($allowanceAry, $infoAry);
                }
            }

            $deductAry = [];
            $dAmount = 0;
            $removeAdvanceLoan = 0;

            if(count($request->deductions) > 0){
                foreach($request->deductions as $infos){
                    // var_dump($infos['name']);
                    if($infos['amount_type'] == "percent"){

                        $dAmount = (floatval(str_replace(',','',$request->gross_salary)) * $infos['percent'])/100;
                    }
                    else{
                        $dAmount = $infos['amount'];
                    }

                    if($infos['name'] != 'Advance-remove'){
                        $infoAry['name'] = $infos['name'];
                        $infoAry['amount'] = sprintf ("%.2f", $dAmount);

                        array_push($deductAry, $infoAry);
                    }  

                    // if($infos['name'] == 'Advance-remove'){
                    //     $removeAdvanceLoan = $removeAdvanceLoan + $infos['amount'];
                    // }  
                }
            }

            $advance_status = '';
            $advance_deduct_amount = '';

            if(count($request->deductions) > 0){
                foreach($request->deductions as $info){

                    if(strpos($info['name'], 'dvance')){
                        $advance_status = 'advance';
                        $advance_deduct_amount = $info['amount']; 
                    }

                    if(strpos($info['name'], 'dvance-remov')){
                        $advance_status = 'advance-remove';
                        $advance_deduct_amount = $info['amount']; 
                    }
                }
            }

            if($advance_status == 'advance'){
                foreach($request->deductions as $info){
                    if(strpos($info['name'], 'dvance')){

                        $advance_amount = $info['amount']; 
                        $loan_id = $info['loan_id']; 
                    }
                }

                $chk = LoanDetails::where('user_id', $request->user_id)->where('loan_id', $loan_id)->where('salary_month', $request->salary_month)->get();

                if(count($chk) == 0){

                    $upd = Loan::find($loan_id);
                    $loan_amount = $upd->loan_amount;
                    $upd->loan_complete_duration = $upd->loan_complete_duration + 1;

                    if($upd->loan_duration == $upd->loan_complete_duration)
                    {
                        $upd->loan_status = 0;
                    }

                    $upd->save();

                    $loanDetailsSum = LoanDetails::where('loan_id', $loan_id)->sum('amount');

                    if(($loan_amount - $loanDetailsSum) < $advance_amount){
                        $advance_amount = $loan_amount - $loanDetailsSum;
                    }
                    
                    $savv = new LoanDetails;
                    $savv->user_id = $request->user_id;
                    $savv->loan_id = $loan_id;
                    $savv->amount = $advance_amount;
                    $savv->salary_month = $request->salary_month;
                    $savv->date = Carbon::now()->format('Y-m-d');
                    $savv->created_by = $this->auth->id;
                    $savv->save();
                }   
            }

            if($advance_status == 'advance-remove'){
                foreach($request->deductions as $info){
                    if(strpos($info['name'], 'dvance-remo')){

                        $advance_amount = $info['amount']; 
                        $loan_id = $info['loan_id']; 
                    }
                }

                $loanDetaiDel = LoanDetails::where('user_id', $request->user_id)->where('loan_id', $loan_id)->where('salary_month', $request->salary_month)->first();

                if(count($loanDetaiDel) > 0){
                    $loanDetaiDel->delete();
                    
                    $upd = Loan::find($loan_id);
                    $upd->loan_complete_duration = $upd->loan_complete_duration - 1;
                    $upd->save();
                }
            }

            // $finalTotalDecuct = $request->total_deduction - $removeAdvanceLoan;
            $finalTotalDecuct = $request->total_deduction;

            // return $request->total_deduction;

	    	$salaries = [
		    	'user_id' => $request->user_id,
                // 'basic_salary' => floatval(str_replace(',','',$request->basic_salary)),
		    	'basic_salary' => $basicSalaryCalculation,
                'salary_in_cash' => round($salaryInCashCalculation),
		    	// 'salary_in_cash' => $salaryInCash,
		    	'salary_month' => $request->salary_month,
		    	'salary_days' => $request->payment_days,
                'salary_pay_type' => $request->salary_pay_type,
		    	'work_hour' => $request->work_hour,
		    	'overtime_hour' => $request->overtime_hour,
		    	'overtime_amount' => $request->overtime_amount,
                'attendance_info' => serialize($request->attendances),
                'allowance_info' => serialize($allowanceAry),
		    	'deduction_info' => serialize($deductAry),
		    	'total_allowance' => floatval(str_replace(',','',$request->total_allowance)),
                // 'total_deduction' => floatval(str_replace(',','',$request->total_deduction)),
		    	'total_deduction' => floatval(str_replace(',','',$finalTotalDecuct)),
                'perhour_salary' => floatval(str_replace(',','',$request->perhour_salary)),
                'perday_salary' => floatval(str_replace(',','',$request->perday_salary)),
                'salary' => $bankSalary,
                // 'gross_salary' => floatval(str_replace(',','',$request->gross_salary)),
                'gross_salary' => $grossSalaryCalculation,
                // 'net_salary' => floatval(str_replace(',','',$request->net_salary)),
		    	'net_salary' => $netSalaryCalculation,
                'total_salary' => floatval(str_replace(',','',$request->total_salary)),
                'remarks' => $request->remarks,
                'payment_procedure' => $current_payment_procedure,
	    	];

	    	$salarys = Salary::where('user_id', $request->user_id)
		    		->where('salary_month', $request->salary_month)
		    		->get();

			DB::beginTransaction();

	    	if($request->salary_pay_type == 'full')
	    	{
	    		$salary_partial = $salarys->where('salary_pay_type', 'partial')->count();
	    		if($salary_partial > 0){
	    			$data['status'] = 'warning';
		            $data['statusType'] = 'OK';
		            $data['code'] = 200;
		            $data['title'] = 'Warning!';
		            $data['message'] = "You can't provide full month salary for this employee. Cause this month he/she already taken partial salary.";
		            return response()->json($data,200);
	    		}

	    		$salary_full = $salarys->where('salary_pay_type', 'full')->count();
	    	
	    		if($salary_full <= 0){
	    			$salaries['created_by'] = $this->auth->id;
	    			$salaries['created_at'] = Carbon::now()->format('Y-m-d h:i:s');
	    			Salary::insert($salaries);
	    		}
	    		else
	    		{
	    			$salaries['updated_by'] = $this->auth->id;
	    			$salaries['updated_at'] = Carbon::now()->format('Y-m-d h:i:s');

	    			Salary::where('user_id', $request->user_id)
			    		->where('salary_month', $request->salary_month)
			    		->where('salary_pay_type', $request->salary_pay_type)
			    		->update($salaries);
	    		}

                // dispatch(new DebitProvidentFundToSalaryGenerate($salaries));
                // dispatch(new LoanInstallmentUpdateToSalaryGenerate($salaries));
	    	}
	    	elseif($request->salary_pay_type == 'partial')
	    	{
	    		$salary = $salarys->where('salary_pay_type', 'full')->count();
	    		// dd($salary);
	    		if($salary > 0){
    				$data['status'] = 'warning';
		            $data['statusType'] = 'OK';
		            $data['code'] = 200;
		            $data['title'] = 'Warning!';
		            $data['message'] = 'This employee full salary already added.';
		            return response()->json($data,200);
    			}

	    		$salary_pay_days = $request->payment_days;
	    		$already_pay_day = $salarys->where('salary_pay_type', 'partial')->sum('salary_days');
	    		$present_month_days = Carbon::parse($request->salary_month)->daysInMonth;
	    	
	    		if($present_month_days > $already_pay_day ){
	    			$salary_due_days = $present_month_days - $already_pay_day;
	    			if($salary_due_days < $salary_pay_days){
	    				$data['status'] = 'warning';
			            $data['statusType'] = 'OK';
			            $data['code'] = 200;
			            $data['title'] = 'Warning!';
			            $data['message'] = 'This employee already taken '.$already_pay_day.' days salary.';
			            return response()->json($data,200);
	    			}
	    		}else{
	    			$data['status'] = 'warning';
		            $data['statusType'] = 'OK';
		            $data['code'] = 200;
		            $data['title'] = 'Warning!';
		            $data['message'] = 'This employee full salary already added.';
		            return response()->json($data,200);
	    		}

	    		$salary = $salarys->where('salary_pay_type', 'full')->count();
    			$salaries['created_by'] = $this->auth->id;
    			$salaries['created_at'] = Carbon::now()->format('Y-m-d h:i:s');
    			Salary::insert($salaries);
	    	}

	    	DB::commit();

            $data['status'] = 'success';
            $data['statusType'] = 'OK';
            $data['code'] = 200;
            $data['title'] = 'Success!';
            $data['message'] = 'Salary Successfully Added!';
            return response()->json($data,200);

	    }catch(\Exception $e){
	    	DB::rollback();
	    	if($request->ajax()){
                $data['status'] = 'danger';
                $data['statusType'] = 'NotOk';
                $data['code'] = 500;
                $data['title'] = 'Error!';
                $data['message'] = 'Salary Not Added!';
                return response()->json($data,500);
            }
	    }
    }


    public function generateSalary($request)
    {
    	$this->validator($request);

    	$branch_id = $request->branch_id;
    	$department_id = $request->department_id;
    	$unit_id = $request->unit_id;
    	$user_id = $request->user_id;
    	$salary_type = $request->salary_type;
    	$salary_month = $request->salary_month;
    	$salary_day = $request->salary_day;

    	$user_ids = $this->getUserIds($branch_id, $department_id, $unit_id, $user_id, $salary_month);
    	$userInfos = $this->getUserInformations($user_ids, $salary_type, $salary_month, $salary_day);

        $days = $userInfos['days'];
    	$month_days = $userInfos['month_days'];
    	$userInfo = $userInfos['userInfo'];

    	$allowance_and_deduction = $userInfos['allowance_and_deduction'];
    	
    	if(Carbon::parse($salary_month)->format('m') == Carbon::now()->format('m'))
    	{
	    	$maybe_present =  $days - Carbon::now()->format('d');
    	}else{
    		$maybe_present = 0;
    	}

        //check for variable food allowance for factory users
        $bsInfo = BasicSalaryInfo::where('salary_info_name', 'like', '%'.'ood'.'%')->where('salary_info_type', 'deduction')->first();

    	$salary_reports = [];

    	foreach($userInfo as $user)
    	{
    		$salary = 0;
	    	$allowances = [];
    		$deductions = [];
    		$total_allowance = 0;
    		$total_deduction = 0;

    		$user_id = $user->id;
    		$work_hour = 8;
            $basic_salary = $user->basic_salary;
    		$salary_in_cash = empty($user->salary_in_cache)?0:$user->salary_in_cache;
            $gross_salary = $user->gross_salary;
    
            $perday_salary = $gross_salary / $month_days; //perday salary gross
    		$perday_salary_with_out_allowance = ($basic_salary + $salary_in_cash) / $month_days;  //perday salary with out allowance
    		$per_hour_salary = $perday_salary / $work_hour;

    		$all_attendance = $user->attendanceTimesheet;

    		if($salary_type == 'month')
    		{
    			$salary_pay_type = 'full';
    		
	    		$attendance_absent = $all_attendance->where('observation',0)->count();
                //prev
                // $attendance_present = $all_attendance->whereIn('observation',[1,5,6])->count();
	    		$attendance_present = $all_attendance->whereIn('observation',[1])->count();
	    		$attendance_leave = $all_attendance->where('observation',2)->count();
	    		$attendance_holiday = $all_attendance->where('observation',3)->count();
                $attendance_only_weekend = $all_attendance->whereIn('observation',4)->count();
	    		$attendance_present_weekend = $all_attendance->whereIn('observation',6)->count();
	    		$attendance_late = $all_attendance->where('late_hour','!=',null)->count();

	    		$attendance_present = $attendance_present + $maybe_present;

	    		$total_attendance = $attendance_absent + $attendance_present + $attendance_leave + $attendance_holiday + $attendance_only_weekend;
                $attendance_weekend = $attendance_only_weekend + $attendance_present_weekend;

	    		$attendance_absent = $attendance_absent + ($days - $total_attendance);

                //prev
                // $payment_days = $attendance_present + $attendance_weekend + $attendance_present_weekend + $attendance_leave;
	    		$payment_days = $days - $attendance_absent;

	    		$attendances = [
	    			'attendance_absent' => $attendance_absent,
	    			'attendance_present' => $attendance_present,
	    			'attendance_leave' => $attendance_leave,
	    			'attendance_holiday' => $attendance_holiday,
                    'attendance_weekend' => $attendance_weekend,
	    			'attendance_present_weekend' => $attendance_present_weekend,
	    			'attendance_late' => $attendance_late,
	    		];

    			$allowance_deduction = $allowance_and_deduction->where('user_id',$user_id)->all();

    			foreach($allowance_deduction as $info)
    			{
    				$percent = 0;
    				$salary_amount = $info->salary_amount;

    				if($info->basicSalaryInfo->salary_info_type == 'allowance')
    				{
    					if($info->salary_amount_type == 'fixed'){
    						$total_allowance = $total_allowance + $salary_amount;
    					}elseif($info->salary_amount_type == 'percent'){
    						$percent = $salary_amount;
                
    						$salary_amount = (($gross_salary) * $salary_amount)/100;
    						$total_allowance = $total_allowance + $salary_amount;
    					}

    					$allowances[] = [
    						'name' => $info->basicSalaryInfo->salary_info_name,
    						'amount_type' => $info->salary_amount_type,
    						'percent' => $percent,
    						'amount' => $salary_amount,
    						'effective_date' => $info->salary_effective_date,
                            'is_provident_fund' => false,
                            'is_loan'  => false,
                            'is_bonus' => false,
    					];

    				}
    				elseif($info->basicSalaryInfo->salary_info_type == 'deduction')
    				{
    					if($info->salary_amount_type == 'fixed'){
    						$total_deduction = $total_deduction + $salary_amount;
    					}elseif($info->salary_amount_type == 'percent'){
    						$percent = $salary_amount;
                        
    						$salary_amount = (($basic_salary) * $salary_amount)/100;
    						$total_deduction = $total_deduction + $salary_amount;
    					}

    					$deductions[] = [
    						'name' => $info->basicSalaryInfo->salary_info_name,
    						'percent' => $percent,
    						'amount' => $salary_amount,
    						'amount_type' => $info->salary_amount_type,
    						'effective_date' => $info->salary_effective_date,
                            'is_provident_fund' => false,
                            'is_loan' => false,
    					];
    				}

    			}

                //check for variable food allowance for factory users
                //find out food allowance come from allowance table or Manual food table
                $foodAllowChk = $allowance_and_deduction->where('user_id',$user_id)->where('basic_salary_info_id', $bsInfo->id)->first();

                if(empty($foodAllowChk)){
                    //food deduction come from manual
                    $getManual = ManualFoodAllowance::where('salary_month', $salary_month)->where('user_id', $user_id)->first();

                    if(!empty($getManual)){
                        $insertLocation = count($deductions);
                        $food_amount = $getManual->total_mill_amount - $getManual->company_pay;
                        $total_deduction = $total_deduction + $food_amount;
                        
                        $deductions[$insertLocation] = [
                                'name' => $bsInfo->salary_info_name,
                                'percent' => 0,
                                'amount' => $food_amount,
                                'amount_type' => 'fixed',
                                'effective_date' => '',
                                'is_provident_fund' => false,
                                'is_loan' => false,
                            ];
                    }
                }
                
                //**************## ====================== ##***************** 

                //total allowance for absent employee
                $total_allowance_per_day = ($total_allowance / $days);
                $total_allowance = ($total_allowance / $days) * $payment_days;
                $bonus_allowance = 0;

                // add bonus to allownace
                // if(count($user->bonus) > 0)
                // {
                //     foreach($user->bonus as $bonus)
                //     {
                //         $bonus_amount = $bonus->bonus_amount;
                //         $total_allowance = $total_allowance + $bonus_amount;
                //         $bonus_allowance = $bonus_allowance + $bonus_amount;

                //         $allowances[] = [
                //             'name' => $bonus->bonusType->bonus_type_name,
                //             'amount_type' => $bonus->bonus_amount_type,
                //             'percent' => $bonus->bonus_type_amount,
                //             'amount' => $bonus_amount,
                //             'effective_date' => $bonus->bonus_effective_date,
                //             'is_provident_fund' => false,
                //             'is_loan' => false,
                //             'is_bonus' => 'bonus',
                //         ];
                //     }
                // }

                // add provident fund to deduction
                // if(count($user->providentFund) > 0)
                // {
                //     foreach($user->providentFund as $providentFund)
                //     {
                //         $provident_fund_amount = (($basic_salary) * $providentFund->pf_percent_amount)/100;
                //         $total_deduction = $total_deduction + $provident_fund_amount;

                //         $deductions[] = [
                //             'name' => 'Provident Fund',
                //             'amount_type' => 'percent',
                //             'percent' => $providentFund->pf_percent_amount,
                //             'amount' => $provident_fund_amount,
                //             'effective_date' => $providentFund->pf_effective_date,
                //             'is_provident_fund' => true,
                //             'is_loan' => false,
                //         ];
                //     }
                // }

                // add loan to deduction
                if(!empty($user->loan))
                {
                    foreach($user->loan as $loan)
                    {
                        if($loan->loan_duration - $loan->loan_complete_duration == 1){
                            $loan_deduct_amount = $loan->loan_amount - ($loan->loan_complete_duration * $loan->loan_deduct_amount);
                        }
                        else{
                            $loan_deduct_amount = $loan->loan_deduct_amount;
                        }

                        $total_deduction = $total_deduction + $loan_deduct_amount;
                        $deductions[] = [
                            'name' => ($loan->loan_or_advance=='advance')?'Advance':'Loan',
                            'amount_type' => 'fixed',
                            'percent' => 0.00,
                            'amount' => $loan_deduct_amount,
                            'effective_date' => '',
                            'is_provident_fund' => false,
                            'is_loan' => true,
                            'loan_id' => $loan->id,
                        ];
                    }
                }
                    // dd($user->loan);
    		}
    		elseif($salary_type == 'day')
    		{
	    		$salary_pay_type = 'partial';
	    		$attendances = [];
    			$payment_days = $days;
    		}

			$total_work_hour = $all_attendance->whereIn('observation',[1,5,6])->sum('total_work_hour');

			$salary = $perday_salary_with_out_allowance * $payment_days; //salary with out allowance
                        
            $salary_in_cash_final = ($salary_in_cash / $month_days) * $payment_days;
			
            $net_salary = 0;
            $total_salary = round(($salary+$total_allowance) - $total_deduction);
    		
    		$salary_reports[] = (object)[
    			'user_id'=> $user->id,
    			'employee_no' =>  $user->employee_no,
    			'full_name' => $user->fullname,
    			'employee_type_id' => $user->employee_type_id,
    			'employee_type' => $user->employeeType->type_name,
    			'basic_salary' => number_format($basic_salary, 2),
                'salary_effective_date' => $user->effective_date,
    			'salary_in_cash' => sprintf ("%.2f", $salary_in_cash ),
    			'salary_month' => $salary_month,
    			'days' => $days,
    			'salary_pay_type' => $salary_pay_type,
    			'payment_days' => $payment_days,
                'overtime_hour' => 0,
                'overtime_amount' => 0.00,
    			'attendances' => $attendances,
    			'allowances'=> $allowances,
                'total_allowance' => sprintf ("%.2f", $total_allowance ),
                'total_allowance_per_day' => sprintf ("%.2f", $total_allowance_per_day ),
    			'bonus_allowance' => sprintf ("%.2f", $bonus_allowance ),
    			'deductions'=> $deductions,
    			'total_deduction' => $total_deduction,
    			'work_hour' => $work_hour,
    			'total_work_hour' => $total_work_hour,
    			'perhour_salary' => sprintf ("%.2f", $per_hour_salary ),
                'perday_salary' => sprintf ("%.2f", $perday_salary ),
    			'perday_salary_with_out_allowance' => sprintf ("%.2f", $perday_salary_with_out_allowance ),
    			'salary' => sprintf ("%.2f", $salary ),
                'gross_salary' => sprintf ("%.2f", $gross_salary ),
    			'net_salary' => sprintf ("%.2f", $net_salary ),
                'total_salary' => sprintf ("%.2f", $total_salary ),
                'remarks' => '',
                'payment_procedure' => $user->payment_procedure,
                'payment_procedure_default' => $user->payment_procedure,
    		];
    	}

    	return $salary_reports;
    }


    protected function validator($request){
    	$this->validate($request,[
            'salary_month' => 'required',
            'salary_type' => 'required',
            'salary_day' => 'required_if:salary_type,day|numeric|max:356',
        ],[],[
        	'salary_type' => 'type',
        	'salary_month' => 'month',
        ]);
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


    protected function getUserInformations($user_ids, $salary_type, $salary_month, $salary_day)
    {
		$start_date = Carbon::parse($salary_month)->format('Y-m-d');
		$end_date = Carbon::parse($salary_month)->format('Y-m-t');
        $month_days = Carbon::parse($salary_month)->daysInMonth;

    	if($salary_type == 'month'){
    		$days = $month_days;

			$userInfo = User::with(['employeeType',
                'attendanceTimesheet'=>function($q)use($start_date, $end_date){
					$q->whereBetween('date', [$start_date, $end_date]);
				},
                'bonus'=>function($q)use($start_date,$end_date){
                    $q->where('approved_by','!=',0)
                        ->whereBetween('bonus_effective_date',[$start_date,$end_date]);
                },'bonus.bonusType',
                'providentFund'=>function($q)use($start_date, $end_date){
					$q->where('pf_status',1)->where('approved_by','!=',0)
                        // ->whereBetween('pf_effective_date',[$start_date,$end_date]);
						->where('pf_effective_date','<=',$start_date)
                        ->orWhere('pf_effective_date','<=',$end_date);
				},
                'loan'=>function($q)use($start_date, $end_date){
					$q->where('loan_status',1)
						->where('approved_by','!=',0)
                        ->where('loan_duration','>','loan_complete_duration')
						->where('loan_start_date','<=', $start_date);
						// ->where('loan_end_date','=>',$end_date);
				}])
				->whereIn('id', $user_ids)->get();
    	}
    	elseif($salary_type == 'day')
    	{
    		$days = $salary_day;
			$userInfo = User::whereIn('id', $user_ids)->get();
    	}

    	$allowance_and_deduction =  EmployeeSalary::with('basicSalaryInfo')
			->whereIn('user_id', $user_ids)
			->where('salary_effective_date','<=',$end_date)
			->get();

    	return ['days' => $days, 'month_days' =>$month_days,  'userInfo' => $userInfo, 'allowance_and_deduction' => $allowance_and_deduction];
    }



}
