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

    	body { background-color: #c0ccff; padding-top: 5em; padding-bottom: 4em;}

		.header,
		.footer {
		    position: fixed; left: 0px; right: 0px; padding: .5em; text-align: center;
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

		.page-num:before { content: counter(page); }

	</style>
</head>
<body style="background-color: #fff;color: black;">
	
	
	<div class="col-md-12 header" align="center">
		
		<h3>{{$company_details[0]->field_value}}</h3>
		<span>
			{{$company_details[2]->field_value}}<br/>
			<b>Employee List</b>
		</span>
	</div>
	
    <div class="col-md-12" style="font-size: 10px;">
        <table class="table">
            <thead>
              <tr>
                <th width="20px">SL</th>
                <th>Photo</th>
                <th>Name</th>
                <th>ID</th>
                <th>Designation</th>
                <th>Department</th>
              </tr>
            </thead>
            <tbody>
            	<?php $sl=1; ?>
            	@foreach($users as $user)
            		@if($user->email != 'hr@afc.com.bd')
		            <tr>
		                <td>{{$sl++}}</td>
		                <td>
		                	@if($user->photo)
	                        	<img src="{{($user->full_image)}}" alt="{{$user->fullname}}" width="50px">
	                        @else
	                        	<img src="{{asset('img/placeholder.png')}}" alt="" width="50px">
	                        @endif
		                </td>
		                <td>{{$user->full_name}}</td>
		                <td>{{$user->employee_no}}</td>
		                <td>{{$user->designation->designation_name}}</td>
		                <td>{{$user->designation->department->department_name}}</td>
		            </tr>
		            @endif
	            @endforeach 
            </tbody>
        </table>
    </div>
    

	<div class="footer row" align="center">
		<dir class="col-md-12">
			<!-- <p>Page <span class="page-num"></span></p> -->
		</dir>
	</div>
  	<script src="{{asset('js/hrms.js')}}"></script>
</body>
</html>