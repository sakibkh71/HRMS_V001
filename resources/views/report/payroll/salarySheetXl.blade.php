<!DOCTYPE html>
<html>
<head>
	<title>Salary Sheet Report</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">
</head>
<body style="background-color: #fff;color: black;">
	
	@if(count($salary_reports) > 0)	
	<div class="col-md-12 header" align="center">
		<table>
			<tr>
				<td>{{$company_details[0]->field_value}}</td>
			</tr>
			<tr>
				<td>{{$company_details[2]->field_value}}</td>
			</tr>
			{{--<tr>--}}
				{{--<td>Department : {{$salary_reports[0]->department}}</td>--}}
			{{--</tr>--}}
			<tr>
				<td>Salary Month : {{$salary_reports[0]->salary_month_format}}</td>
			</tr>
		</table>
	</div>
	
    <div class="col-md-12" style="font-size: 8px;">
        <table class="table">
            <thead>
              <tr>
                <th width="15px;">SL</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Working<br/> Days</th>
                <th>Basic</th>
                <th>House Ren.</th>
                <th>Medical</th>
                <th>Conv.</th>
                <th>Gross</th>
                <th>Tax</th>
                <th>Fooding</th>
                <th>Penalty</th>
                <th>Total Ded.</th>
                <th>Net. Salary</th>
                <th>Bank</th>
                <th>Cash</th>
                <th>Signature</th>
              </tr>
            </thead>

            <tbody>
            	<?php 
            		$sl=1; 
            		$basicTotal = 0;
            		$houseRentTotal = 0;
            		$medicalTotal = 0;
            		$convTotal = 0;
            		$grossTotal = 0;
            		$taxTotal = 0;
            		$foodTotal = 0;
            		$penaltyTotal = 0;
            		$deductionTotal = 0;
            		$salaryTotal = 0;
            		$bankTotal = 0;
            		$cashTotal = 0;
            	?>
            	
            	@foreach($salary_reports as $payroll)
            	<?php 
	            	$house_rent = '-';
	            	$medi = '-';
	            	$conv = '-';
	            	$tax = '-';
	            	$fooding = '-';
	            	$penalty = '-';
	            ?>
	            <tr>
	                <td>{{$sl++}}</td>
	                <td>
	                	({{$payroll->employee_no}})
	                    {{$payroll->full_name}}
	                </td>
	                <td>{{$payroll->employee_designation}}</td>
	                <td>{{$payroll->salary_days}}</td>
	                <td>
	                	{{$payroll->basic_salary}}
	                	<?php 
	                		$basicTotal = $basicTotal + $payroll->basic_salary; 
	                	?>
	                </td>
                	@if(count($payroll->allowances) > 0)
                		@foreach($payroll->allowances as $info)
                			@if(strpos($info['name'], 'ouse'))
						    	<?php 
						    		$house_rent = $info['amount']; 
						    		$houseRentTotal = $houseRentTotal+$house_rent;
						    	?>
						    @endif
						    @if(strpos($info['name'], 'edica'))
						    	<?php 
						    		$medi = $info['amount']; 
						    		$medicalTotal = $medicalTotal+$medi;
						    	?>
						    @endif
						    @if(strpos($info['name'], 'onv'))
						    	<?php 
						    		$conv = $info['amount']; 
						    		$convTotal = $convTotal+$conv;
						    	?>
						    @endif
                		@endforeach
                		<td>{{$house_rent}}</td>
		                <td>{{$medi}}</td>
		                <td>{{$conv}}</td>
                	@else
	                	<td>-</td>
		                <td>-</td>
		                <td>-</td>
                	@endif
	                <td>
	                	{{$payroll->gross_salary}}
	                	<?php 
	                		$grossTotal = $grossTotal + $payroll->gross_salary;
	                	?>
	                </td>
                	@if(count($payroll->deductions) > 0)
						@foreach($payroll->deductions as $info)
							@if(strpos($info['name'], 'ax'))
						    	<?php 
						    		$tax = $info['amount']; 
						    		$taxTotal = $taxTotal + $tax;
						    	?>
						    @endif
						    @if(strpos($info['name'], 'enalt'))
						    	<?php 
						    		$penalty = $info['amount']; 
						    		$penaltyTotal = $penaltyTotal + $penalty;
						    	?>
						    @endif
						    @if(strpos($info['name'], 'ood'))
						    	<?php 
						    		$fooding = $info['amount']; 
						    		$foodTotal = $foodTotal + $fooding;
						    	?>
						    @endif
						@endforeach
						<td>{{$tax}}</td>
		                <td>{{$fooding}}</td>
		                <td>{{$penalty}}</td>
					@else
						<td>-</td>
		                <td>-</td>
		                <td>-</td>
					@endif
	                <td>
	                	{{$payroll->total_deduction}}
	                	<?php $deductionTotal = $deductionTotal + $payroll->total_deduction; ?>
	                </td>
	                <td>
	                	{{$payroll->net_salary}}
	                	<?php $salaryTotal = $salaryTotal + $payroll->net_salary; ?>
	                </td>
	                <td>
	                	{{$payroll->salary}}
	                	<?php $bankTotal = $bankTotal + $payroll->salary; ?>
	                </td>
	                <td>
	                	{{$payroll->salary_in_cash}}
	                	<?php $cashTotal = $cashTotal + $payroll->salary_in_cash; ?>
	                </td>
	                <td></td>
	            </tr>
	            @endforeach 	
            </tbody>
            <tfoot>
	            <tr>
	            	<th></th>
	                <th>Total</th>
	                <th></th>
	                <th></th>
	                <th>{{$basicTotal}}</th>
	                <th>{{$houseRentTotal}}</th>
	                <th>{{$medicalTotal}}</th>
	                <th>{{$convTotal}}</th>
	                <th>{{$grossTotal}}</th>
	                <th>{{$taxTotal}}</th>
	                <th>{{$foodTotal}}</th>
	                <th>{{$penaltyTotal}}</th>
	                <th>{{$deductionTotal}}</th>
	                <th>{{$salaryTotal}}</th>
	                <th>{{$bankTotal}}</th>
	                <th>{{$cashTotal}}</th>
	                <th></th>
	            </tr>
            </tfoot>
        </table>
    </div>
    @endif

    {{--
	<div class="footer row" align="center">
		<dir class="col-md-12">
			<table class="table borderless">
				<tr>
					@if(count($signatures) > 0)
						@foreach($signatures as $sign)
							@if(!empty($sign['name']) || !empty($sign['desig']))
							<td>
								--------------------------------<br/>
								{{!empty($sign['name'])?$sign['name']:''}}<br/>
								{{!empty($sign['desig'])?$sign['desig']:''}}

							</td>
							@endif
						@endforeach
					@endif
				</tr>
			</table>
		</dir>
	</div>
	--}}

  	
</body>
</html>