<!DOCTYPE html>
<html>
<head>
	<title>Bank/Cash Advice Report</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">

	<style type="text/css">
		table, th, td {
		   border: 1px solid black !important;
		   padding-top: 1px !important;
		   padding-bottom: 1px !important;
		}

		table{
			padding-left: 0px !important;
		}
	</style>
</head>
<body style="background-color: #fff; color: black;">
	<div class="container" style="font-size: 9px; padding-left: 0px; @if($salary_reports['advice_type'] == 'bank')margin-top: 110px;@endif">

			@if($salary_reports['advice_type'] == 'bank')
				@if(!empty($bank_letter_head))
					{!! $bank_letter_head !!}
				@endif
			@else
				@if(count($salary_reports['reports']) > 0)	
				<div class="col-md-12" align="center">
					<h3>Bank/Cash Advice : {{$salary_reports['reports'][0]->salary_month}}</h3>
				</div>
				@endif
		    @endif

		    <div class="col-md-12">
		        <table class="table table-bordered">
		            <thead>
		              <tr>
		                <th width="18px">SL</th>
		                @if($salary_reports['advice_type'] == 'bank')
		                	<th width="150px">Account Name</th>
		                	<th>Account No.</th>
		                	<th>Branch Code</th>
		                	<th>Bank Amount</th>
		              	@elseif($salary_reports['advice_type'] == 'cash')
		              		<th width="280px;">Employee</th>
		              		<th>Cash Amount</th>
		              	@else
		              		<th width="150px;">Employee</th>
		              		<th>Account Name</th>
			                <!-- <th>Account No.</th>
			                <th>Branch Code</th> -->
			                <th>Bank Amount</th>
			                <th>Cash Amount</th>
		              	@endif
		              	<th width="15px;">Dr/Cr</th>
		                <th width="90px">Remarks</th>
		              </tr>
		            </thead>

		            <tbody>
		            	<?php $sl=1; ?>
		            	@foreach($salary_reports['reports'] as $payroll)
			            <tr>
			                <td>{{$sl++}}</td>
			                @if($salary_reports['advice_type'] == 'bank')
			                	<td>{{$payroll->bank_account_name}} </td>
				                <td>{{$payroll->bank_account_no}}</td>
				                <td>{{$payroll->bank_branch_name}}</td>
			                	<td>{{$payroll->salary}}</td>
			              	@elseif($salary_reports['advice_type'] == 'cash')
			              		<td>
				                    {{$payroll->full_name}}
				                    ({{$payroll->employee_no}})
			                	</td>
			              		<td>{{$payroll->salary_in_cash}}</td>
			              	@else
			              		<td>
			                    	{{$payroll->full_name}} 
			                    	({{$payroll->employee_no}})
			                	</td>
			              		<td>{{$payroll->bank_account_name}}</td>
				                <!-- <td>{{$payroll->bank_account_no}}</td>
				                <td>{{$payroll->bank_branch_name}}</td> -->
				                <td>{{$payroll->salary}}</td>
				                <td>{{$payroll->salary_in_cash}}</td>
			              	@endif
			                <td></td>
			                <td>{{$payroll->salary_month}}</td>
			            </tr>
			            @endforeach
		            </tbody>
		        </table>
		    </div>
  	</div>


  <script src="{{asset('js/hrms.js')}}"></script>
</body>
</html>