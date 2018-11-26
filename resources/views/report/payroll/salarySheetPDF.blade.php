<!DOCTYPE html>
<html>
<head>
	<title>Salary Sheet Report</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">

	<style type="text/css">
		table, th, td {
		   border: 1px solid black !important;
		   padding-top: 1px !important;
		   padding-bottom: 1px !important;
		}

		table{
			margin-top: 10px;
		}

    	body { 
    		background-color: #c0ccff; 
    		padding-top: 5em; 
    		padding-bottom: 4em;
    		margin-left:  -10px;
			padding-left: -10px;
    	}

		.header,
		.footer {
		    position: fixed; left: 0px; right: 0px; padding: .5em; text-align: center;
		    /*position: fixed;*/
		}
		.header {
		    top: -40px;
		}
		.footer {
		    bottom: 25px;
		    font-size: 8px;
		}

		.footer table{
			border: 0 !important;
		}

		.borderless tr, .borderless td, .borderless th {
		    border: 0 !important;
		    padding: 5px !important;
		    background-color: white !important;
		}

	</style>
</head>
<body style="background-color: #fff;color: black;">
	
	@if(count($salary_reports) > 0)	
	<div class="col-md-12 header" align="center">
		<h3 style="margin-bottom: 0px !important;">{{$company_details[0]->field_value}}</h3>
		<span>
			{{$company_details[2]->field_value}}<br/>
			@if($department_id > 0)
				Department : {{$salary_reports[0]->department}}<br/>
			@endif
			Salary Month : {{$salary_reports[0]->salary_month_format}}
		</span>
	</div>
	
    <div class="col-md-12" style="font-size: 8px;">
        <table class="table">
            <thead>
            	<tr>
	                <th width="13px;" rowspan="2">SL</th>
	                <th width="130px;" rowspan="2">Name</th>
	                <th width="110px;" rowspan="2">Designation</th>
	                <th rowspan="2">Working<br/> Days</th>
	                <th rowspan="2">Gross</th>
	                <th colspan="4" class="text-center">Earning(TK.)</th>
	                <th rowspan="2">Earned<br/>Salary</th>
	                <th colspan="4" class="text-center">Deduct(TK.)</th>
	                <th rowspan="2">Net. Salary</th>
	                <th rowspan="2">Bank</th>
	                <th rowspan="2">Cash</th>
	                <th rowspan="2">Sign.</th>
              	</tr>
              	<tr>
	                <th>Basic</th>
	                <th>House Ren.</th>
	                <th>Medical</th>
	                <th>Conv.</th>
	                <th>Tax</th>
	                <th>Fooding</th>
	                <th>Adjust.<br>/Others</th>
	                <th>Total Ded.</th>
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
            		$adjustTotal = 0;
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
	            	$adjustment = '-';
	            ?>
	            <tr>
	                <td>{{$sl++}}</td>
	                <td>
	                	({{$payroll->employee_no}})
	                    {{$payroll->full_name}}
	                </td>
	                <td>{{$payroll->employee_designation}}</td>
	                <td>{{$payroll->salary_days}}</td>
	                <td>{{$payroll->fixedSalary}}</td>
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
						    @if(strpos($info['name'], 'dvanc'))
						    	<?php 
						    		$adjustment = $info['amount']; 
						    		$adjustTotal = $adjustTotal + $adjustment;
						    	?>
						    @endif
						@endforeach
						<td>{{$tax}}</td>
		                <td>{{$fooding}}</td>
		                <td>{{$adjustment}}</td>
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
	                <th></th>
	                <th>{{$basicTotal}}</th>
	                <th>{{$houseRentTotal}}</th>
	                <th>{{$medicalTotal}}</th>
	                <th>{{$convTotal}}</th>
	                <th>{{$grossTotal}}</th>
	                <th>{{$taxTotal}}</th>
	                <th>{{$foodTotal}}</th>
	                <th>{{$adjustTotal}}</th>
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

    <!-- ================================ -->


<!-- <table class="table">
  
  <tr>
    <th rowspan="2">Produced111</th>
    <th colspan="2" scope="colgroup">Mars</th>
    <th colspan="2" scope="colgroup">Venus</th>
	<th rowspan="2">Produced111</th>
  </tr>
  <tr>
	 
    <th scope="col">Produced</th>
    <th scope="col">Sold</th>
    <th scope="col">Produced</th>
    <th scope="col">Sold</th>
  </tr>
  <tr>
    <td scope="row">5000000</td>
    <td>50,000</td>
    <td>30,000</td>
    <td>100,000</td>
    <td>80,000</td>
	  <td>44000</td>
  </tr>
  <tr>
    <td scope="row">400000</td>
    <td>10,000</td>
    <td>5,000</td>
    <td>12,000</td>
    <td>9,000</td>
	  <td>44000</td>
  </tr>
	
</table> -->
    <!-- ==================================== -->

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
  	<script src="{{asset('js/hrms.js')}}"></script>
</body>
</html>