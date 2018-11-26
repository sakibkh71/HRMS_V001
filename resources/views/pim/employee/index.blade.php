@extends('layouts.hrms')
@section('content')

<section class="p10" id="employee_list">

  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><i class="glyphicons glyphicons-group"></i></span>
            <strong>Employee Search</strong>

            <!-- ************************* -->
            <!-- Menual Emp Upload -->
              <span class="pull-right"><button class="btn btn-danger btn-sm" v-on:click.prevent="modal_open('#manual_employee_modal')">Upload Employee</button></span>
            <!-- ************************* -->


        </div>

        <div class="panel-body">
          <!-- <form v-on:submit.prevent="generateAdviceSheet"> -->
          <form method="Post" action="{{url('employee/index')}}">

            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Branch :</label>
                  <select class="form-control input-sm" name="branch_id" v-model="branch_id">
                      <option value="0">...All Branch...</option>
                      @foreach($branches as $binfo)
                        @if(in_array("employee/onlyBranch", session('userMenuShare')))
                          @if(Auth::user()->branch_id == $binfo->id)
                            <option value="{{$binfo->id}}">{{$binfo->branch_name}}</option>
                          @endif
                        @else 
                          <option value="{{$binfo->id}}">{{$binfo->branch_name}}</option>
                        @endif
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Department :</label>
                  <select class="form-control input-sm" name="department_id" v-model="department_id">
                      <option value="0">...All Department...</option>
                      @foreach($departments as $dinfo)
                      <option value="{{$dinfo->id}}">{{$dinfo->department_name}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Unit :</label>
                  <select class="form-control input-sm" name="unit_id" v-model="unit_id">
                      <option :value="0">...All Unit...</option>
                      <option v-for="(unit,index) in units" :value="unit.id" v-text="unit.unit_name"></option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Blood Group :</label>
                  <select class="form-control input-sm" name="blood_id" v-model="blood_id">
                      <option value="0">...Select Blood Group...</option>
                      @foreach($blood_groups as $blood)
                        <option value="{{$blood->id}}">{{$blood->blood_name}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Religion :</label>
                  <select class="form-control input-sm" name="religion_id" v-model="religion_id">
                      <option value="0">...Select Religion...</option>
                      @foreach($religions as $religion)
                        <option value="{{$religion->id}}">{{$religion->religion_name}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Gender :</label>
                  <select class="form-control input-sm" name="gender" v-model="gender">
                      <option value="0">...Select Gender...</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                  </select>
                </div>
              </div>
              
              <div class="col-md-2">
                <div class="form-group">
                  <label class="control-label">Divisions(Permanent Addrss) :</label>
                  <select class="form-control input-sm" name="division_id" v-model="division_id">
                      <option value="0">...Select Devisions...</option>
                      @foreach($divisions as $divi)
                        <option value="{{$divi->id}}">{{$divi->division_name}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label class="control-label">Districts(Permanent Addrss) :</label>
                  <select class="form-control input-sm" name="district_id" v-model="district_id">
                      <option :value="0">...Select District...</option>
                      <option v-for="(district,index) in districts" :value="district.id" v-text="district.district_name"></option>
                  </select>
                </div>
              </div>

              <div class="col-md-1" style="padding-top:22px!important">
                <div class="form-group">
                  <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-dark">Search</button>
                </div>
              </div>
            </form>

              <form action="{{url('employee/pdfEmpList')}}" method="POST" target="__blank">
                {{ csrf_field() }}

                <input type="hidden" name="pdf_branch_id" v-model="branch_id">
                <input type="hidden" name="pdf_department_id" v-model="department_id">
                <input type="hidden" name="pdf_unit_id" v-model="unit_id">

                <input type="hidden" name="pdf_blood_id" v-model="blood_id">
                <input type="hidden" name="pdf_religion_id" v-model="religion_id">
                <input type="hidden" name="pdf_gender" v-model="gender">
                <input type="hidden" name="pdf_division_id" v-model="division_id">
                <input type="hidden" name="pdf_district_id" v-model="district_id">

                <div class="col-md-1" style="padding-top:22px!important">
                  <div class="form-group">
                    <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-info">PDF</button>
                  </div>
                </div>
              </form>
              <form action="{{url('employee/xlEmpList')}}" method="POST" target="__blank">
                {{ csrf_field() }}

                <input type="hidden" name="pdf_branch_id" v-model="branch_id">
                <input type="hidden" name="pdf_department_id" v-model="department_id">
                <input type="hidden" name="pdf_unit_id" v-model="unit_id">

                <input type="hidden" name="pdf_blood_id" v-model="blood_id">
                <input type="hidden" name="pdf_religion_id" v-model="religion_id">
                <input type="hidden" name="pdf_gender" v-model="gender">
                <input type="hidden" name="pdf_division_id" v-model="division_id">
                <input type="hidden" name="pdf_district_id" v-model="district_id">

                <div class="col-md-1" style="padding-top:22px!important">
                  <div class="form-group">
                    <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-primary">Excel</button>
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel">
          <div class="panel-heading">
              <div class="panel-title">
                  <span class="glyphicon glyphicon-tasks"></span>Employee Information
                  <span class="pull-right">
                  <?php 
                    $chkUrl = \Request::segment(1);
                  ?>
                  @if(in_array($chkUrl."/add", session('userMenuShare')))
                    <a href="{{url('employee/add')}}" class="btn btn-sm btn-dark btn-gradient dark"><span class="glyphicons glyphicons-user_add"></span> &nbsp; Add Employee</a>
                  @endif
                  </span>
              </div>
          </div>
          <div class="panel-body pn">
              <table class="table table-striped table-hover" id="datatable1" cellspacing="0" width="100%">
                  <thead>
                  <tr class="bg-dark">
                      <th>SL:</th>
                      <th>Employee No</th>
                      <th>Employee Name</th>
                      <th>Email Address</th>
                      <th>Designation</th>
                      <th>Department</th>
                      <th>Image</th>
                      <!-- <th>Created By</th> -->
                      <!-- <th>Updated By</th> -->
                      <th>Created Date</th>
                      <!-- <th>Updated Date</th> -->
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tfoot>
                  <tr class="bg-dark">
                      <th>SL:</th>
                      <th>Employee No</th>
                      <th>Employee Name</th>
                      <th>Email Address</th>
                      <th>Designation</th>
                      <th>Department</th>
                      <th>Image</th>
                      <!-- <th>Created By</th> -->
                      <!-- <th>Updated By</th> -->
                      <th>Created Date</th>
                      <!-- <th>Updated Date</th> -->
                      <th>Action</th>
                  </tr>
                  </tfoot>
                  <tbody>
                  <?php $sl=1;?>
                  @foreach($users as $user)
                    @if($user->email != 'hr@afc.com.bd')
                        <tr>
                           <td>{{$sl}}</td>
                           <td>{{$user->employee_no}}</td>
                           <td>{{$user->fullname}}</td>
                           <td>{{$user->email}}</td>
                           <td>{{$user->designation->designation_name}}</td>
                           <td>{{$user->designation->department->department_name}}</td>
                      
                           <td>
                              @if($user->photo)
                               <img src="{{$user->full_image}}" alt="{{$user->fullname}}" width="50px">
                               @else
                               <img src="{{asset('img/placeholder.png')}}" alt="" width="50px">
                              @endif
                           </td>
                           <!-- <td>@if($user->createdBy) {{$user->createdBy->fullname}} @else Maybe system @endif</td> -->
                           <!-- <td>@if($user->updatedBy) {{$user->updatedBy->fullname}} @else Maybe system @endif</td> -->
                           <td>{{$user->created_at}}</td>
                           <!-- <td>{{$user->updated_at}}</td> -->
                           <td>
                              @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                  <div class="btn-group">
                                     <a href="{{url('/employee/edit/'.$user->id)}}" class="btn btn-xs btn-primary">
                                         <i class="glyphicons glyphicons-pencil"></i>
                                     </a>
                                  </div>
                              @endif
                              
                              <div class="btn-group">
                                <a href="{{url('/employee/view/'.$user->employee_no)}}" class="btn btn-xs btn-success">
                                    <i class="glyphicons glyphicons-eye_open"></i>
                                </a>
                              </div>

                                @if($user->status != 55)
                                  @if(in_array($chkUrl."/leave", session('userMenuShare')))
                                      <div class="btn-group">
                                        <!-- leave button -->
                                          <button type="button" class="btn btn-info btn-xs" onclick="showLeaveData({{$user->id}})" data-toggle="modal" data-target=".showLeaveData">
                                            <i class="fa fa-sign-out" aria-hidden="true"></i>    
                                          </button>
                                      </div>
                                  @endif
                                  @if(in_array($chkUrl."/status", session('userMenuShare')))
                                      <div class="btn-group">
                                        <!-- emp status change btn -->
                                        @if(in_array($chkUrl."/delete", session('userMenuShare')))
                                           <button type="button" class="btn btn-warning btn-xs" @click="EmployeeStatus({{$user->id}})" data-toggle="modal" data-target=".EmployeeStatus">
                                             @if($user->status==1)
                                                {{"Active"}}
                                             @elseif($user->status==2)
                                                {{"Retired"}}
                                             @elseif($user->status==3)
                                                {{"Released"}}
                                             @elseif($user->status==4)
                                                {{"Resigned"}}
                                             @elseif($user->status==5)
                                                {{"Terminated"}}
                                             @elseif($user->status==6)
                                                {{"Dismissed"}}
                                             @elseif($user->status==7)
                                                {{"Contract Terminated"}}
                                             @elseif($user->status==8)
                                                {{"Abscond"}}
                                             @elseif($user->status==9)
                                                {{"Transfer"}}
                                             @elseif($user->status==10)
                                                {{"Deactive"}}
                                             @else
                                                {{"Undefined"}}
                                             @endif
                                           </button>
                                        @endif
                                      </div>
                                  @endif
                                  @if(in_array($chkUrl."/permission", session('userMenuShare')))
                                      <div class="btn-group">
                                        <!-- emp permission button-->
                                        <button type="button" class="btn btn-xs btn-success" onclick="showData({{$user->id}})" data-toggle="modal" data-target=".showData"><i class="fa fa-lock" aria-hidden="true"></i></button>
                                      </div>
                                  @endif
                                  @if(in_array($chkUrl."/empType", session('userMenuShare')))
                                      <div class="btn-group">
                                        <!-- emp type change button -->
                                        <button type="button" class="btn btn-xs btn-info" @click="showEmpType({{$user->id}})" data-toggle="modal" data-target=".showEmpType"><i class="fa fa-user-circle" aria-hidden="true"></i></button>
                                      </div>
                                  @endif
                                  @if(in_array($chkUrl."/companySwitch", session('userMenuShare')))
                                      <div class="btn-group">
                                        <!-- Multi Company Button -->
                                        <button type="button" class="btn btn-info btn-xs" onclick="showMultiCompanyData({{$user->id}}, '{{$user->email}}')" data-toggle="modal" data-target=".showMultiCompanyData">
                                            <i class="fa fa-building-o" aria-hidden="true"></i>
                                        </button>
                                      </div>
                                  @endif
                                @else
                                  <div class="btn-group">
                                    <button type="button" class="btn btn-dark btn-xs">
                                      Only for access
                                    </button>
                                  </div>
                                @endif
                           </td>
                        </tr>
                    
                      <?php $sl++;?>
                    @endif
                  @endforeach
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div>



  <div id="manual_employee_modal" style="max-width:450px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">
                <i class="fa fa-rocket"></i>Manual Employee Upload
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                  <form class="admin-form" method="post" action="{{url('/employee/manual')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                      <div class="col-md-12">
                          <div class="form-group" :class="{'has-error':errors.date}">
                            <label class="control-label pb5">Upload CSV file: <span class="text-danger">*</span></label>

                            <label class="field prepend-icon append-button file">
                              <span class="button btn-primary">Choose CSV File</span>
                              <input type="file" required="required" class="gui-file" name="csv_file" id="file1" onChange="document.getElementById('uploader1').value = this.value;">
                              <input type="text" class="gui-input" id="uploader1" placeholder="Please Select A File">
                              <label class="field-icon">
                                <i class="fa fa-upload"></i>
                              </label>
                            </label>
                            <span class="input-footer">
                              <strong>File format must be : </strong> Like this demo file. <a href="{{url('/employee/demo')}}">Download Demo</a>
                            </span>
                              <span v-if="errors.date" class="text-danger" v-text="errors.date[0]"></span>
                          </div>
                      </div>
                    </div>

                    <hr class="short alt">

                    <div class="section row mbn">
                        <div class="col-sm-6 pull-right">
                            <p class="text-left">
                                <button type="submit" name="upload_attendance" class="btn btn-dark btn-gradient btn-sm"><i class="fa fa-upload"></i> &nbsp; Upload CSV
                                </button>
                            </p>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </div>
      

@include('pim.employee.modals.permission')
@include('pim.employee.modals.leave')
@include('pim.employee.modals.employee_status')
@include('pim.employee.modals.employee_type')
@include('pim.employee.modals.multi_company')

</section>



@section('script')

<script type="text/javascript">

$(document).ready( function() {
      $('#datatable1').dataTable( {
            "pageLength": 50,
            "aLengthMenu": [[50, 100, 150, -1], [50, 100, 150, "All"]], 
            stateSave: true,
            "bDestroy": true
        } );
} )

//js code for permission start

function showData(id){
  
  $('.hdn_id').val('');
  $('input:checkbox').removeAttr('checked');
  //first it clean previous data....
  $('.hdn_id').val(id);

  $.ajax({
      url: "{{url('/employee/permission')}}/"+id,
      type: 'GET',
  })
  .done(function(data){
   
      if(data.length > 0){
          jQuery.each(data, function(index, item) {
              $('input[value='+item.menu_id+']').prop("checked", true);
          });
      }else{
          $('input:checkbox').removeAttr('checked');
      }
  })
  .fail(function(){
      swal("Error", "Data not removed.", "error");
  });
}
//js code permission finished

//js code for LEAVE start
function showLeaveData(id){
  
  $('.hdn_id').val('');
  $('input:checkbox').removeAttr('checked');
  // first it clean previous data....
  $('.hdn_id').val(id);

  $.ajax({
      url: "{{url('/employee/leave')}}/"+id,
      type: 'GET',
  })
  .done(function(data){
      var bUrl = "{{url('leave/details/')}}";
      $('.detailsLink').html("<a href='"+bUrl+"/"+data.personalInfo.employee_no+"'>"+data.personalInfo.first_name+" "+data.personalInfo.last_name+"</a>");

      if(data.individual_user_leaves.length > 0){
          jQuery.each(data.individual_user_leaves, function(index, item) {
              $('input[value='+item.leave_type_id+']').prop("checked", true);
          });
      }else{
          $('input:checkbox').removeAttr('checked');
      }
  })
  .fail(function(){
      swal("Error", "Leave Error ...", "error");
  });
}
//js code LEAVE finished

//js code for MultiCompany start
function showMultiCompanyData(id, email){

  $('.hdn_id').val('');
  $('.hdn_email').val('');
  $('input:checkbox').removeAttr('checked');
  // first it clean previous data....
  $('.hdn_id').val(id);
  $('.hdn_email').val(email);

  $.ajax({
      url: "{{url('/employee/companys')}}/"+id,
      type: 'GET',
  })
  .done(function(data){
    
      if(data.length > 0){
          jQuery.each(data, function(index, item) {
              $('input[value='+item+']').prop("checked", true);
          });
      }else{
          $('input:checkbox').removeAttr('checked');
      }
  })
  .fail(function(){
      swal("Error", "Emp Companyes Error ...", "error");
  });
}
//js code MultiCompany finished


  new Vue({
    el: '#employee_list',
    data:{
      department_id:0,
      unit_id:0,
      branch_id:0,
      blood_id: 0,
      religion_id: 0,
      gender: 0,
      division_id: 0,
      district_id: 0,
      units:[],
      users:[],
      districts:[],

      user_id: '',
      emp_type_user_id: '',
      type_name: '',
      HTMLcontent: '',
      show_history: [],
      emp_type_history: [],
      emp_all_types:[],
      emp_current_type: null,
      up_coming_type: null,
      upcomming_status: null,
      validity: null,
      final_or_current_type_id: null,
      final_or_current_type_from_date: null,
      final_or_current_type_to_date: null,
      final_or_current_type_map_id: null,

      //*********** Menual Emp Attendance ***********
      errors:[],
      //*********************************************
    },

    watch:{
      department_id: function(id){
        if(id !=0){
            this.getUnitByDepartmentId(id);
        }else{
          this.units = [];
        }
        this.getEmployee();
      },

      unit_id: function(){
        this.getEmployee();
      },

      branch_id: function(){
        this.getEmployee();
      },

      division_id: function(id){
        this.getDistrictByDivision(id);
        // alert(id);
      },

    },

    mounted: function(){

      this.branch_id = {{$old_branch_id}};
      this.department_id = {{$old_department_id}};
      this.unit_id = {{$old_unit_id}};
      this.blood_id = {{$old_blood_id}}; 
      this.religion_id = {{$old_religion_id}};
      this.gender = '{{$old_gender}}';
      this.division_id = {{$old_division_id}};
      this.district_id = {{$old_district_id}};
    },

    methods:{

      getUnitByDepartmentId(id){
          axios.get('/get-unit-by-department-id/'+id).then(response => {
              this.units = response.data;
              // console.log(this.designations);
          });
      },

      getDistrictByDivision(id){
          axios.get('/get-district-by-division/'+id).then(response => {
              this.districts = response.data;
              // console.log(this.designations);
          });
      },

      getEmployee(){
        axios.get('/payroll/index/'+this.branch_id+'/'+this.department_id+'/'+this.unit_id).then(response => {
            this.users = response.data;
            // console.log(this.supervisors);
        });
      },

      returnStatusName(id){

        var stName = "Undefined";

        if(id == 1){
          stName = "Active";
        }
        else if(id == 2){
          stName = "Retired";
        }
        else if(id == 3){
          stName = "Released";
        }
        else if(id == 4){
          stName = "Resigned";
        }
        else if(id == 5){
          stName = "Terminated";
        }
        else if(id == 6){
          stName = "Dismissed";
        }
        else if(id == 7){
          stName = "Contract Terminated";
        }
        else if(id == 8){
          stName = "Abscond";
        }
        else if(id == 9){
          stName = "Transfer";
        }
        else if(id == 10){
          stName = "Deactive";
        }

        return stName;
      },

      returnTypeName(id){
        if(id==1){
          return "Permanent";
        }
        else if(id == 2){
          return "Trainee/Probation";
        }
        else if(id == 3){
          return "Part time";
        }
        else if(id == 4){
          return "Special Contract";
        }
        else{
          return "Undefined";
        }
      },

      EmployeeStatus(data){

        this.user_id = data;

        //getEmployeeStatus get history .. if no history.. 
        //it generate one history Automatically 
        axios.get('/employee/get_employee_status/'+this.user_id).then(response => {
          
          this.show_history = response.data.status_history;
          this.upcomming_status = response.data.upcomming_status;
          this.validity = response.data.validity;
          this.final_or_current_type_id = response.data.final_or_current_type.employee_type_id;
          this.final_or_current_type_map_id = response.data.final_or_current_type.id;
          this.final_or_current_type_from_date = response.data.final_or_current_type.from_date;
          this.final_or_current_type_to_date = response.data.final_or_current_type.to_date;
        });
      },

      empUpdateStatus(e){
        
        // var pathArray = window.location.pathname.split( '/' );
        
        var formData = new FormData(e.target);

        // formData.append('file', document.getElementById('file').files[0]);

        axios.post("updateEmployeeStatus", formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then((response) => { 
    
            this.HTMLcontent = null;

            if(response.data.title == 'error'){
              swal({
                title: response.data.title+"!",
                text: response.data.message,
                type: response.data.title,
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Done",
                closeOnConfirm: true
              });
            }
            else{
              swal({
                  title: response.data.title+"!",
                  text: response.data.message,
                  type: response.data.title,
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Done",
                  closeOnConfirm: false
              },
              function(){
                  location.href=location.href;
              });
            }
              
        })
        .catch((error) => {
            
            if(error.response.status != 200){ //error 422
                var errors = error.response.data;

                var errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                
                this.HTMLcontent = errorsHtml;
            }
        });
      },

      showEmpType(id){

        this.emp_type_user_id = id;

        axios.get('/employee/get_employee_types_history/'+this.emp_type_user_id).then(response => {
          
          this.emp_type_history = response.data.history;
          this.emp_all_types = response.data.emp_types;
          this.emp_current_type = response.data.current_type;
          this.up_coming_type = response.data.up_coming_type;
        });
      },
      deleteUpComming(id, typee){
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false
        },
        function(){

            //typee = EmpType or typee == Emp Stauts
            axios.get('/employee/delete_up_comming/'+id+'/'+typee).then(response => {
              
            });

            swal({
                title: "Deleted!",
                text: "Successfully Removed",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Done",
                closeOnConfirm: false
            },
            function(){
                location.href=location.href;
            });
            
        });
      },
      updateEmpType(e){
        
        // var pathArray = window.location.pathname.split( '/' );
        
        var formData = new FormData(e.target);

        // formData.append('file', document.getElementById('file').files[0]);

        axios.post("updateEmpType", formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
        .then((response) => { 

            this.HTMLcontent = null;

            if(response.data.title == 'error'){
              swal({
                title: response.data.title+"!",
                text: response.data.message,
                type: response.data.title,
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Done",
                closeOnConfirm: true
              });
            }
            else{
              swal({
                  title: response.data.title+"!",
                  text: response.data.message,
                  type: response.data.title,
                  showCancelButton: false,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Done",
                  closeOnConfirm: false
              },
              function(){
                  location.href=location.href;
              });
            }
              
        })
        .catch((error) => {
            
            if(error.response.status != 200){ //error 422
                var errors = error.response.data;

                var errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                
                this.HTMLcontent = errorsHtml;
            }
        });
      },


      //***************************************
      //########### Menual Emp Csv FIle UPload 
      modal_open(form_id) {
        this.errors = [];

        $.magnificPopup.open({
            removalDelay: 300,
            items: {
                src: form_id
            },
            callbacks: {
                beforeOpen: function (e) {
                    var Animation = "mfp-zoomIn";
                    this.st.mainClass = Animation;
                }
            },
            midClick: true
        });
      },
      //***************************************
    }
  });
</script>

@endsection


@endsection