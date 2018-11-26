<!DOCTYPE html>
<html>
<head>
	<title>Journal Sheet Report</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">
</head>
<body style="background-color: #fff;color: black;">
	
	@if(count($reports) > 0)	
	<div class="col-md-12 header" align="center">
		<table>
			<tr>
				<td>{{$company_details[0]->field_value}}</td>
			</tr>
			<tr>
				<td>{{$company_details[2]->field_value}}</td>
			</tr>
			<tr>
				<td>{{"Salary Journal Voucher"}}</td>
			</tr>
			<tr>
				<td>Salary Month : {{$reports[0]->salary_month}}</td>
			</tr>
		</table>
	</div>
	
    <div class="col-md-12" style="font-size: 8px;">
        <table class="table">
            <thead>
              	<tr>
	                <th width="13px;">SL</th>
	                <th>Name</th>
	                <th width="26px;">L.F</th>
	                <th>Debit Taka</th>
	                <th>Credit Taka</th>
              	</tr>
            </thead>

            <tbody>
            	<?php 
            		$sl=1;
            	?>
            	
            	@foreach($reports as $payroll)
            	
	            <tr>
	                <td>{{$sl++}}</td>
	                <td>
	                    {{$payroll->full_name}} ({{$payroll->employee_no}})
	                </td>
	                <td></td>
	                <td></td>
	                <td>{{$payroll->amount}}</td>
	            </tr>
	            @endforeach 
            </tbody>  
            <tfoot>
	            <tr>
	            	<th></th>
	                <th>Total</th>
	                <th></th>
	                <th>{{$reports[0]->total_loan}}</th>
	                <th>{{$reports[0]->total_loan}}</th>
	            </tr>
            </tfoot>
        </table>
    </div>
    @endif
</body>
</html>