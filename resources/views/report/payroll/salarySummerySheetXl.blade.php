<!DOCTYPE html>
<html>
<head>
	<title>Salary Sheet Summery Report</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">
</head>
<body style="background-color: #fff;color: black;">
	
	@if(count($reports) > 0)	
	<div class="col-md-12 header" align="center">
		<h3 style="margin-bottom: 0px !important;">{{$company_details[0]->field_value}}</h3>
		<span>
			{{$company_details[2]->field_value}}<br/>
			<b>{{"Salary Sheet Summery"}}</b><br/>
			<b>{{$reports[0]->salary_month}}</b>
		</span>
	</div>
	
    <div class="col-md-12" style="font-size: 8px;">
        <table class="table">
            <thead>
            	<tr>
                <th rowspan="2">SL</th>
                <th rowspan="2" class="text-center">Particulars</th>
                <th colspan="2" class="text-center">Salary Adjust</th>
                <th colspan="2" class="text-center">Bank</th>
                <th colspan="2" class="text-center">Cash</th>
                <th colspan="2" class="text-center">Total</th>
              </tr>
              <tr>
                <th>{{$reports[0]->only_month_prev}}</th>
                <th>{{$reports[0]->only_month}}</th>
                <th>{{$reports[0]->only_month_prev}}</th>
                <th>{{$reports[0]->only_month}}</th>
                <th>{{$reports[0]->only_month_prev}}</th>
                <th>{{$reports[0]->only_month}}</th>
                <th>{{$reports[0]->only_month_prev}}</th>
                <th>{{$reports[0]->only_month}}</th>
              </tr>
            </thead>

            <tbody>
            	<?php 
            		$sl=1; 
            	?>
            	
            	@foreach($reports as $payroll)
	            <tr>
	                <td>{{$sl++}}</td>
	                <td>{{$payroll->dep_name}}</td>
	                <td>{{$payroll->advance_salary_prev}}</td>
	                <td>{{$payroll->advance_salary}}</td>
	                <td>{{$payroll->bank_salary_prev}}</td>
	                <td>{{$payroll->bank_salary}}</td>
	                <td>{{$payroll->cash_salary_prev}}</td>
	                <td>{{$payroll->cash_salary}}</td>
	                <td>{{$payroll->total_prev}}</td>
	                <td>{{$payroll->total}}</td>
	            </tr>
	            @endforeach 
	       
            </tbody>
            <tfoot>
	            <tr>
	            	<td colspan="2"><b>Total</b></td>
	                <td>{{$reports[0]->total_advance_salary_prev}}</td>
	                <td>{{$reports[0]->total_advance_salary}}</td>
	                <td>{{$reports[0]->total_bank_prev}}</td>
	                <td>{{$reports[0]->total_bank}}</td>
	                <td>{{$reports[0]->total_cash_prev}}</td>
	                <td>{{$reports[0]->total_cash}}</td>
	                <td>{{$reports[0]->total_total_prev}}</td>
	                <td>{{$reports[0]->total_total}}</td>
	            </tr>
            </tfoot>
        </table>
    </div>
    @endif
</body>
</html>