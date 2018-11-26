@extends('layouts.hrms')
@section('content')

    <!-- Begin: Content -->
    <section id="content" class="animated fadeIn">
        <div class="row">

            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">All Designations</span>
                        
                        <?php 
                          $chkUrl = \Request::segment(1);
                        ?>
                        @if(in_array($chkUrl."/add", session('userMenuShare')))
                            <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".designationAdd" style="margin-top: 12px;">Add Designation</button>
                        @endif
                    </div>
                    <div class="panel-body">
                        @if($designations->count() > 0)
                        <table class="table table-hover" id="datatable">
                        <thead>
                            <tr class="success">
                                <th>sl</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Level</th>
                                <th>Description</th>
                                <th>Effective Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sl=1; ?>
                            @foreach($designations as $designation)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $designation->designation_name }}</td>
                                <td>{{ $designation->department->department_name }}</td>
                                <td>{{ $designation->level->level_name }}</td>
                                <td>{{ $designation->designation_description }}</td>
                                <td>{{ $designation->designation_effective_date }}</td>
                                <td>{{$designation->status==1?"Active":"Inactive"}}</td>
                                <td>
                                    @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                        <button data-id="{{$designation->id}}" type="button" class="btn btn-sm btn-primary edit-btn" data-toggle="modal" data-target=".designationEdit">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                    @endif
                                    @if(in_array($chkUrl."/delete", session('userMenuShare')))
                                        <button onclick="wantToDelete({{$designation->id}})" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash-o"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                        @else
                            {{"No level available..."}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End: Content -->    

    <!-- designationAdd modal start -->
    <div class="modal fade bs-example-modal-lg designationAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Designation</h4>
              </div>

              <form class="form-horizontal designation-add" role="form">
              <div class="modal-body">
                    <div id="create-form-errors">
                    
                    </div>
                {{-- action="{{ url('designation/add') }}" --}}
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-3 control-label">Name <span class="text-danger">*</span></label>

                        <div class="col-md-9">
                            <input id="name" type="text" class="form-control input-sm" name="name" value="{{ old('name') }}" autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                        <label for="department" class="col-md-3 control-label">Department <span class="text-danger">*</span></label>

                        <div class="col-md-9">
                            <select id="department" name="department" class="form-control department input-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}" @if(old('department') == $department->id){{"selected"}}@endif>{{$department->department_name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('department'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('department') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                        <label for="level" class="col-md-3 control-label">Level <span class="text-danger">*</span></label>

                        <div class="col-md-9">
                            <select id="level" name="level" class="form-control level input-sm">
                                <option value="">Select Level</option>
                                @foreach($levels as $level)
                                    <option value="{{$level->id}}" @if(old('level') == $level->id){{"selected"}}@endif>{{$level->level_name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('level'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('level') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
                        <label for="details" class="col-md-3 control-label">Details</label>

                        <div class="col-md-9">
                            <textarea id="details" class="form-control input-sm" name="details" autofocus>{{ old('details') }}</textarea>

                            @if ($errors->has('details'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('details') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="effective_date" class="col-md-3 control-label">Effective Date</label>
                        <div class="col-md-9">
                            <input type="text" name="effective_date" class="gui-input datepicker form-control input-sm" placeholder="">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="radio-custom radio-success mb5">
                                        <input type="radio" id="active" name="status" checked="" value="1">
                                        <label for="active">Active</label>
                                    </div>    
                                </div>
                                <div class="col-md-4">
                                    <div class="radio-custom radio-danger mb5">
                                        <input type="radio" id="inactive" name="status" value="0">
                                        <label for="inactive">Inactive</label>
                                    </div>    
                                </div>
                            </div>     
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Designation</button>
              </div>
              </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- designationAdd modal end -->

    <!-- designationEdit modal start -->
    <div class="modal fade bs-example-modal-lg designationEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Designation</h4>
              </div>
              <div class="modal-body">

                <div id="edit-form-errors">
                    
                </div>

                <form class="form-horizontal designation-edit" role="form">

                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="" class="edit-id">

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-3 control-label">Name</label>

                        <div class="col-md-9">
                            <input id="name" type="text" class="form-control input-sm edit-name" name="name" value="" autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                        <label for="department" class="col-md-3 control-label">Department</label>

                        <div class="col-md-9">
                            <select id="department" name="department" class="form-control department input-sm edit-department">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->department_name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('department'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('department') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                        <label for="level" class="col-md-3 control-label">Level</label>

                        <div class="col-md-9">
                            <select id="level" name="level" class="form-control level input-sm edit-level">
                                <option value="">Select Level</option>
                                @foreach($levels as $level)
                                    <option value="{{$level->id}}">{{$level->level_name}}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('level'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('level') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
                        <label for="details" class="col-md-3 control-label">Details</label>

                        <div class="col-md-9">
                            <textarea id="details" class="form-control input-sm edit-details" name="details"></textarea>

                            @if ($errors->has('details'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('details') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="effective_date" class="col-md-3 control-label">Effective Date</label>
                        <div class="col-md-9">
                            <input type="text" name="effective_date" class="gui-input datepicker form-control input-sm edit_effective_date" placeholder="">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-9 col-md-offset-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="radio-custom radio-success mb5">
                                        <input type="radio" class="edit-status" id="edit-active" name="status" value="1">
                                        <label for="edit-active">Active</label>
                                    </div>    
                                </div>
                                <div class="col-md-4">
                                    <div class="radio-custom radio-danger mb5">
                                        <input type="radio" class="edit-status" id="edit-inactive" name="status" value="0">
                                        <label for="edit-inactive">Inactive</label>
                                    </div>    
                                </div>
                            </div>     
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Designation</button>
              </div>

              </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- designationEdit modal end -->   

@endsection

@section('script')
<script type="text/javascript">

function wantToDelete(id){

    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this data !",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    },
    function(){
        $.ajax({
            url: "{{url('/designation/delete')}}/"+id,
            type: 'GET',
        })
        .done(function() {
            swal({
                title: "Deleted!",
                text: "Your data has been deleted.",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Done",
                closeOnConfirm: false
            },
            function(){
                location.href=location.href;
            });
        })
        .fail(function(){
            swal("Error", "Data not removed.", "error");
        });
    });
}
    
jQuery(document).ready(function() {

    // Init DataTables
    $('#datatable').dataTable({
      "aoColumnDefs": [{
        'bSortable': false,
        'aTargets': [-1]
      }],
      "oLanguage": {
        "oPaginate": {
          "sPrevious": "",
          "sNext": ""
        }
      },
      "iDisplayLength": 25,
      "aLengthMenu": [
        [25, 50, -1],
        [25, 50, "All"]
      ],
      "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
      "oTableTools": {
        "sSwfPath": "vendor/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
      }
    });

    $(".pagination").addClass(" pull-right");



    //Create Designation --post form

    $('.designation-add').submit(function(event) {
                   
        event.preventDefault();
        var form = $('.designation-add');

        $.ajax({
            url: "{{url('designation/add')}}",
            dataType:'json',
            data: form.serialize(),
            type: "POST", 

            success: function(response)
            {
                swal({
                    title: response.title,
                    text: response.message,
                    type: response.title == "Success"?"success":"error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Done",
                    closeOnConfirm: false
                },
                function(){
                    location.href=location.href;
                });
            },
            error: function(data)
            {
                var errors = data.responseJSON;

                errorsHtml = '<div class="alert alert-danger"><ul>';

                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                    
                $( '#create-form-errors' ).html( errorsHtml );
            }
        });
    });

    //Create Designation --post form

    $('.designation-edit').submit(function(event) {
                   
        event.preventDefault();
        var form = $('.designation-edit');

        $.ajax({
            url: "{{url('designation/edit')}}",
            dataType:'json',
            data: form.serialize(),
            type: "POST", 

            success: function(response)
            {
                swal({
                    title: response.title,
                    text: response.message,
                    type: response.title == "Success"?"success":"error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Done",
                    closeOnConfirm: false
                },
                function(){
                    location.href=location.href;
                });
                
            },
            error: function(data)
            {
                var errors = data.responseJSON;

                errorsHtml = '<div class="alert alert-danger"><ul>';

                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></di>';
                    
                $( '#edit-form-errors' ).html( errorsHtml );
            }
        });
    });

  });

$('.edit-btn').click(function(event) {

    var id = $(this).attr("data-id");

    $.ajax({
        url: "{{url('designation/edit')}}/"+id,
        type: 'GET',
    })
    .done(function(data) {
        
        $('.edit-id').val(data['id']);
        $('.edit-name').val(data['designation_name']);
        $('.edit-department').val(data['department_id']);
        $('.edit-level').val(data['level_id']);
        $('.edit-details').val(data['designation_description']);
        $('.edit_effective_date').val(data['designation_effective_date']);
        $(".edit-status[value=" + data['status'] + "]").prop('checked', true);
    })
    .fail(function() {
        //console.log("error");
        alert('error');
    });
    
});


</script>
@endsection