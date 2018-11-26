@extends('layouts.hrms')
@section('content')
    <!-- Begin: Content -->
    <section id="content" class="">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">All Levels</span>
                        <?php 
                          $chkUrl = \Request::segment(1);
                        ?>
                        @if(in_array($chkUrl."/add", session('userMenuShare')))
                        <a href="{{url('levels/add')}}">
                            <button type="button" class="btn btn-xs btn-success pull-right" style="margin-top: 12px;">Add Level</button>
                        </a>
                        @endif
                    </div>
                    <div class="panel-body">
                        @if($levels->count() > 0)
                        <table class="table table-hover" id="datatable">
                        <thead>
                            <tr class="success">
                                <th>sl</th>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Salary</th>
                                <th>Salary Info</th>
                                <th>Permissions</th>
                                <th>Description</th>
                                <th>Effective Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sl=1; ?>
                            @foreach($levels as $level)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $level->level_name }}</td>
                                <td>
                                    @if($level->parent_id > 0)
                                        {{ !empty($level->parent)?$level->parent->level_name:'' }}
                                    @endif
                                </td>
                                <td>{{ $level->level_salary_amount }}</td>
                                <td>
                                    @if($level->salaryInfo->count() > 0)
                                        @foreach($level->salaryInfo as $info)
                                            @if(!empty($info->basicSalaryInfo->salary_info_name))
                                                {{ $info->basicSalaryInfo->salary_info_name }}
                                                ({{$info->basicSalaryInfo->salary_info_amount_status == 0?"%":"$"}})
                                                {{ ": ".$info->amount }}
                                                <br/>
                                            @endif
                                        @endforeach
                                    @endif    
                                </td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-success" onclick="showData({{$level->id}})" data-toggle="modal" data-target=".showData">Permissions</button>
                                </td>
                                <td>{{ $level->description }}</td>
                                <td>{{ $level->level_effective_date }}</td>
                                <td>{{$level->status==1?"Active":"Inactive"}}</td>
                                <td class="text-center">
                                    @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                        <a href="{{url("levels/edit/$level->id")}}" title="">
                                            <button type="button" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </a>
                                    @endif
                                    @if(in_array($chkUrl."/delete", session('userMenuShare')))
                                        <button onclick="wantToDelete({{$level->id}})" class="btn btn-sm btn-danger">
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

    <!-- showData modal start -->
    <div class="modal fade bs-example-modal-lg showData" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">
            <form class="form-horizontal department-create" action="{{url('levels/updatePermission')}}" method="post" id="department-create">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Level Permissions</h4>
                    </div>
                    <div class="modal-body">
                        <div id="create-form-errors">
                            
                        </div>
                        {{ csrf_field() }}

                        <input type="hidden" value="" name="hdn_id" class="hdn_id">
                        <div class="form-group">
                            <div class="col-md-11 col-md-offset-1">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <span style="color:#bd0100;">
                                            <b>*Effect existing users of this level:</b>
                                        </span>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="radio-custom radio-danger mb5">
                                                    <input type="radio" id="inactive" name="status" checked="checked" value="0">
                                                    <label for="inactive">No</label>
                                                </div>    
                                            </div>
                                            <div class="col-md-3">
                                                <div class="radio-custom radio-success mb5">
                                                    <input type="radio" id="active" name="status" value="1">
                                                    <label for="active">Yes</label>
                                                </div>    
                                            </div>
                                        </div>     
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-11 col-md-offset-1">
                                @foreach($modules_permission as $info)
                                    <div class="row">
                                        <label for="name">
                                            {{ $info->module_name }}
                                        </label>
                                    </div>
                                    <div class="row">
                                        @foreach($info->menus as $mInfo)
                                            @if($mInfo->menu_parent_id == 0)
                                            <div class="row">
                                                <div class="col-md-2">
                                                    {{$mInfo->menu_section_name}}
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="level_menus[{{$mInfo->id}}]" value="0">
                                                        <input type="checkbox" name="level_menus[{{$mInfo->id}}]" value="{{$mInfo->id}}"> 
                                                        {{$mInfo->menu_name}}
                                                    </div>
                                                @foreach($mInfo->child_menu as $cInfo)
                                                    <div class="col-md-2">
                                                        <input type="hidden" name="level_menus[{{$cInfo->id}}]" value="0">
                                                        <input type="checkbox" name="level_menus[{{$cInfo->id}}]" value="{{$cInfo->id}}"> 
                                                        {{$cInfo->menu_name}}
                                                    </div>
                                                @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach      
                                    </div>
                                @endforeach             
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        @if(in_array($chkUrl."/edit", session('userMenuShare')))
                            <button type="submit" class="btn btn-primary">Update Permission</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- showData modal end -->  

@endsection

@section('script')
<script type="text/javascript">

function showData(id){
    
    $('.hdn_id').val('');
    $('input:checkbox').removeAttr('checked');
    //first it clean previous data....
    $('.hdn_id').val(id);

    $.ajax({
        url: "{{url('/levels/permission')}}/"+id,
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
            url: "{{url('/levels/delete')}}/"+id,
            type: 'GET',
        })
        .done(function(data) {

            swal({
                title: data.title+"!",
                text: data.message+"!",
                type: data.title,
                showCancelButton: false,
                confirmButtonColor: "#8cc63d",
                confirmButtonText: "Done",
                closeOnConfirm: false
            },
            function(){
                location.href=location.href;
            });
        })
        .fail(function(){
            
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

  });


</script>
@endsection
