@extends('layouts.hrms')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <style type="text/css" media="screen">
        .sm-height {
            padding: 4px;
        }

        .btn-custom{
            color: #ffffff;
            text-decoration: none;
        }

        .btn-custom:hover{
            color: #ffffff;
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
<div id="mainDiv">
    <!-- Begin: Content -->
    <section id="content" class="">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Resign Application</span>

                        <?php 
                          $chkUrl = \Request::segment(1);
                        ?>
                        
                        <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".dataAdd" style="margin-top: 12px;">Resign Application</button>
                        
                    </div>
                    <div class="panel-body">
                        <div id="showData">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                    <tr class="success">
                                        <th>sl</th>
                                        <th>User Name</th>
                                        <th>Reason</th>
                                        <th>Effective Date</th>
                                        <th>Supervisor</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl=1; ?>
                                    @if(count($resignInfos) > 0)
                                        @foreach($resignInfos as $info)
                                            <tr>
                                                <td>{{$sl++}}</td>
                                                <td>{{$info->user->full_name}}</td>
                                                <td>{{$info->reason}}</td>
                                                <td>{{$info->effective_date}}</td>
                                                <td>{{!empty($info->supervisor)?$info->supervisor->full_name:'--'}}</td>
                                                <td></td>
                                                <td>
                                                    <div class="btn-group remov-cls-toggle">
                                                        <button type="button" class="btn btn-xs" :class="resignStatusBtn({{$info->resign_status}})" v-text="showResignStatus({{$info->resign_status}})">
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-xs dark dropdown-toggle" :class="resignStatusBtn({{$info->resign_status}})" data-toggle="dropdown" aria-expanded="false" v-show="{{$info->resign_status}} != 4">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        
                                                        <ul class="dropdown-menu toggle-cls" role="menu">
                                                            <li>
                                                              <a @click="changeStatus({{$info->id}}, 1)" v-show="{{$info->resign_status}} != 1 && {{$info->resign_status}} != 2 && {{$info->resign_status}} != 3">Pending</a>
                                                            </li>
                                                            <li>
                                                              <a @click="changeStatus({{$info->id}}, 3)" v-show="{{$info->resign_status}} != 3">Accepted</a>
                                                            </li>
                                                            <li>
                                                              <a @click="changeStatus({{$info->id}}, 4)" v-show="{{$info->resign_status}} != 4">Cancel</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End: Content -->   

    <!-- dataAdd modal start -->
    <div class="modal fade bs-example-modal-lg dataAdd" role="dialog" aria-labelledby="myLargeModalLabel" id="modalDataAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Resign Application</h4>
                </div>
                <form class="form-horizontal" @submit.prevent="saveData" id="addFormData" method="post" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div id="create-form-errors">
                        </div>

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="emp_name" class="col-md-3 control-label">Employee Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select2 name="emp_name" v-model="emp_name" style="
                                width: 100%;color: #555555;
                                border: 1px solid #dddddd;
                                transition: border-color ease-in-out .15s;
                                height: 30px;
                                padding: 5px 10px;
                                font-size: 12px;
                                line-height: 1.5;
                                border-radius: 2px;"
                                >
                                    <option value="">Select Employee Name For Resignation</option>
                                    <option v-for="(info,index) in users" 
                                        :value="info.id" 
                                        v-text="info.first_name+' '+info.last_name+' ('+info.employee_no+') - '+info.designation.designation_name"
                                    ></option>
                                </select2>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="resign_reason" class="col-md-3 control-label">Reason <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="resign_reason" v-model="resign_reason" class="form-control input-sm" placeholder="Application reason"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="effective_date" class="col-md-3 control-label">Effective Date <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" id="effective_date" name="effective_date" v-model="effective_date" class="gui-input datepicker form-control input-sm" placeholder="From">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close-btn" id="modal-close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Process</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- dataAdd modal end --> 

    <!-- salary Info Edit modal start ..... dataEdit-->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalDataEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Resign Application</h4>
                </div>
                
                <form class="form-horizontal" @submit.prevent="updateData" id="updateFormData" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div id="edit-form-errors">
                        </div>

                        {{ csrf_field() }}
                        <input type="hidden" name="hdn_id" v-model="hdn_id">

                        <div class="form-group">
                            <label for="edit_emp_name" class="col-md-3 control-label">Employee Name </label>
                            <div class="col-md-9">
                                <select name="edit_emp_name" disabled="" v-model="edit_emp_name" class="form-control input-sm">
                                    <option v-for="(info,index) in users" 
                                        :value="info.id" 
                                        v-text="info.first_name+' '+info.last_name+' - '+info.designation.designation_name"
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="emp_supervisor" class="col-md-3 control-label">Supervisor Name </label>
                            <div class="col-md-9">
                                <select name="emp_supervisor" disabled="" v-model="emp_supervisor" class="form-control input-sm">
                                    <option v-for="(info,index) in users" 
                                        :value="info.id" 
                                        v-text="info.first_name+' '+info.last_name+' - '+info.designation.designation_name"
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_effective_date" class="col-md-3 control-label">Effective Date <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" id="edit_effective_date" name="edit_effective_date" v-model="edit_effective_date" class="gui-input datepicker form-control input-sm" placeholder="Effective Date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Reason <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="edit_resign_reason" v-model="edit_resign_reason" class="form-control input-sm" placeholder="Application reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default modal-close-btn" id="modal-edit-close-btn" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
              
            </div>
        </div>
    </div>
    <!-- salary Info Edit modal end --> 
</div>

@endsection

@section('script')


<script src="https://unpkg.com/vue-select@2.2.0"></script>
<script src="{{asset('/js/resign.js')}}"></script>

<script>
    $('.toggle-cls').click(function(event) {
        
        document.getElementsByClassName("remov-cls-toggle").remove("open");
    });

    $('.edit-btn-Cls').click(function(event) {
        setTimeout(function() {
            $('#modalDataEdit').modal();
        }, 200);
    });

</script>
@endsection