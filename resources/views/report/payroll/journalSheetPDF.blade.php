<!DOCTYPE html>
<html>
<head>
	<title>Journal Sheet Report</title>
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
	
	@if(count($reports) > 0)	
	<div class="col-md-12 header" align="center">
		<h3 style="margin-bottom: 0px !important;">{{$company_details[0]->field_value}}</h3>
		<span>
			{{$company_details[2]->field_value}}<br/>
			<span style="font-size: 18px;">{{"Salary Journal Voucher"}}</span><br/>
			<span style="font-size: 15px;"><b>{{$reports[0]->salary_month}}</b></span><br/>
		</span>
	</div>
	
    <div class="col-md-12" style="font-size: 8px;margin-top: 15px;">
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
        <br>
        <span style="font-size: 12px;">Inword: Taka {{$inWord}} Only.</span><br>
        <span style="font-size: 12px;">Description: The Amount Adjusted Advance Against Salary.</span>
    </div>
    @endif

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

  	<!-- <script src="{{asset('js/hrms.js')}}"></script> -->
</body>
</html>