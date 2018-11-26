<!DOCTYPE html>
<html>
<head>
	<title>Bank Advice</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">

</head>
<body style="background-color: #fff; color: black;">
	<div class="container" style="font-size: 9px; padding-left: 0px;">

			<table>
			@if(count($salary_reports['reports']) > 0)	
				@if($salary_reports['advice_type'] == 'bank')
					<tr>
						<td>
							Bank Advice : {{$salary_reports['reports'][0]->salary_month}}
						</td>
					</tr>
				@else
					<tr>
						<td>Bank/Cash Advice : {{$salary_reports['reports'][0]->salary_month}}</td>
					</tr>
				@endif
		    @endif
		    </table>

		    <div class="col-md-12">
		        <table>
		            <thead>
		              <tr>
		                <th width="15px;">SL</th>
		                @if($salary_reports['advice_type'] == 'bank')
		                	<th>Account Name</th>
		                	<th>Account No.</th>
		                	<th>Branch Code</th>
		                	<th>Bank Amount</th>
		              	@elseif($salary_reports['advice_type'] == 'cash')
		              		<th>Employee</th>
		              		<th>Cash Amount</th>
		              	@else
		              		<th>Employee</th>
		              		<th>Account Name</th>
			                <!-- <th>Account No.</th>
			                <th>Branch Code</th> -->
			                <th>Bank Amount</th>
			                <th>Cash Amount</th>
		              	@endif
		              	<th>Dr/Cr</th>
		                <th>Remarks</th>
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