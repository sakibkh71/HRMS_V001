<!DOCTYPE html>
<html>
<head>
	<title>Employee List</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">
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
                <th width="15px;">SL</th>
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
		                		<?php 
		                			$modityPhotoName = explode("storage", $user->full_image);
		                			$modityPhotoName = "/storage".$modityPhotoName[1];
		                		?>
		                		@if(file_exists("{{ public_path().''.$modityPhotoName }}"))
		                        	<img src="{{ public_path().''.$modityPhotoName }}" alt="{{$user->fullname}}" width="50px">
		                        @else
		                        	<img src="{{public_path().'/img/placeholder.png'}}" alt="" width="50px">
		                        @endif
	                        @else
	                        		<img src="{{public_path().'/img/placeholder.png'}}" alt="" width="50px">
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