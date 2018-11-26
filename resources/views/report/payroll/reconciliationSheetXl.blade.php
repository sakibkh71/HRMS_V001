<!DOCTYPE html>
<html>
<head>
	<title>Reconciliation Sheet PDF</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">
</head>
<body style="background-color: #fff; color: black;">
	<div class="container" style="padding-left: 0px;">
			<div class="col-md-12 header" align="center">
				<table>
					<tr>
						<td><h3>{{$company_details[0]->field_value}}</h3></td>
					</tr>
					<tr>
						<td><b>Salary Reconciliation Statement</b></td>
					</tr>
					<tr>
						<td>
							<b>For The Month of {{date("F-Y", strtotime($salary_month))}}</b>
						</td>
					</tr>
				</table>
			</div>

		    <div class="col-md-12" style="font-size: 9px;">
		        <table class="table table-bordered">
		            <thead>
						<tr>
							<th>Particulars</th>
							<th>Amount</th>
							<th>Total Amount</th>
						</tr>
		            </thead>
		            <tbody>
		            	<?php 
		            		$total_add = 0;
		            		$total_less = 0;
		            	?>
		            	<tr>
		            		<!-- date("F-Y", strtotime($salary_month)) -->
		            		<td><b>Salary {{date('F-Y', strtotime(date($salary_month)." -1 month"))}}</b></td>
		            		<td></td>
		            		<td><b>{{$reports['salary_prv_month']}}</b></td>
		            	</tr>
		            	<tr>
		            		<td colspan="3">
		            			<b>Add</b>
		            		</td>
		            	</tr>
		            	@if($reports['normal_add'])
			            	@foreach($reports['normal_add'] as $add)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$add['name']}}</td>
			            			<td>{{$add['amount']}}</td>
			            			<?php 
			            				$total_add = $total_add + $add['amount'];
			            			?>
			            			<td></td>
			            		</tr>
			            	@endforeach
			            @endif
			            @if($reports['other_add'])
			            	@foreach($reports['other_add'] as $add)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$add['name']}}</td>
			            			<td>{{$add['amount']}}</td>
			            			<?php 
			            				$total_add = $total_add + $add['amount'];
			            			?>
			            			<td></td>
			            		</tr>
			            	@endforeach
			            @endif
			            <tr>
		            		<td colspan="3">
		            			<b>Incriment</b>
		            		</td>
		            	</tr>
		            	@if($reports['increment_add'])
			            	@foreach($reports['increment_add'] as $add)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$add['name']}}</td>
			            			<td>{{$add['amount']}}</td>
			            			<?php 
			            				$total_add = $total_add + $add['amount'];
			            			?>
			            			<td></td>
			            		</tr>
			            	@endforeach
			            @endif
			            <tr>
		            		<td colspan="3">
		            			<b>New Join</b>
		            		</td>
		            	</tr>
		            	@if($reports['new_join_add'])
			            	@foreach($reports['new_join_add'] as $add)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$add['name']}}</td>
			            			<td>{{$add['amount']}}</td>
			            			<?php 
			            				$total_add = $total_add + $add['amount'];
			            			?>
			            			<td><b>{{ $total_add }}</b></td>
			            		</tr>
			            	@endforeach
			            @endif
			            <tr>
			            	<td colspan="2"></td>
			            	<td><b>{{ $total_add + $reports['salary_prv_month'] }}</b></td>
			            </tr>
			            <tr>
		            		<td colspan="3">
		            			<b>Less</b>
		            		</td>
		            	</tr>
		            	@if($reports['normal_less'])
			            	@foreach($reports['normal_less'] as $less)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$less['name']}}</td>
			            			<td>{{$less['amount']}}</td>
			            			<?php 
			            				$total_less = $total_less + $less['amount'];
			            			?>
			            			<td></td>
			            		</tr>
			            	@endforeach
			            @endif
			            @if($reports['other_less'])
			            	@foreach($reports['other_less'] as $less)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$less['name']}}</td>
			            			<td>{{$less['amount']}}</td>
			            			<?php 
			            				$total_less = $total_less + $less['amount'];
			            			?>
			            			<td></td>
			            		</tr>
			            	@endforeach
			            @endif
			            <tr>
		            		<td colspan="3">
		            			<b>Resign</b>
		            		</td>
		            	</tr>
		            	@if($reports['resign_less'])
			            	@foreach($reports['resign_less'] as $less)
			            		<tr>
			            			<td>({{$add['emp_no']}}) {{$less['name']}}</td>
			            			<td>{{$less['amount']}}</td>
			            			<?php 
			            				$total_less = $total_less + $less['amount'];
			            			?>
			            			<td></td>
			            		</tr>
			            	@endforeach
			            @endif
			            <tr>
			            	<td colspan="2"></td>
			            	<td><b>{{ $total_less }}</b></td>
			            </tr>
			            <tr>
		            		<!-- date("F-Y", strtotime($salary_month)) -->
		            		<td><b>Salary {{date("F-Y", strtotime($salary_month))}}</b></td>
		            		<td></td>
		            		<td><b>{{$reports['salary_corrent_month']}}</b></td>
		            	</tr>
		            </tbody>
		        </table>
		    </div>
  	</div>


  <script src="{{asset('js/hrms.js')}}"></script>
</body>
</html>