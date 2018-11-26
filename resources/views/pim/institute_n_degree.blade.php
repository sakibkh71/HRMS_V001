@extends('layouts.hrms')

@section('style')
    
@endsection

@section('content')
<div id="mainDiv">
    <!-- Begin: Content -->
    <section id="content" class="animated fadeIn">
        <div class="row">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Institutes</span>
                        <?php 
                          $chkUrl = \Request::segment(1);
                        ?>
                        @if(in_array($chkUrl."/add", session('userMenuShare')))
                            <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".instituteAdd" style="margin-top: 12px;">Add Institute</button>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div id="showData">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                    <tr class="success">
                                        <th>sl</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl =1; ?>
                                    @foreach($institutes as $ins)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $ins->institute_name }}</td>
                                        <td>
                                            @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                                <button type="button" @click="dataEdit({{$ins->id}}, 'Institute')" class="btn btn-sm btn-primary edit-btn" data-toggle="modal" data-target=".dataEdit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                            @if(in_array($chkUrl."/delete", session('userMenuShare')))
                                                <button class="btn btn-sm btn-danger" @click="deleteInstitute({{$ins->id}})"><i class="fa fa-trash-o"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Degrees</span>
                        @if(in_array($chkUrl."/add", session('userMenuShare')))
                            <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".degreeAdd" style="margin-top: 12px;">Add Degree</button>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div id="showData">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                    <tr class="success">
                                        <th>sl</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sl =1; ?>
                                    @foreach($degrees as $deg)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $deg->degree_name }}</td>
                                        <td>
                                            @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                                <button type="button" @click="dataEdit({{$deg->id}}, 'Degree')" class="btn btn-sm btn-primary edit-btn" data-toggle="modal" data-target=".dataEdit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                            @if(in_array($chkUrl."/delete", session('userMenuShare')))
                                            <button class="btn btn-sm btn-danger" @click="deleteDegree({{$deg->id}})"><i class="fa fa-trash-o"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End: Content -->   

    <!-- Add Institute modal start -->
    <div class="modal fade bs-example-modal-lg instituteAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalInstituteAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Institute</h4>
              </div>
              <form class="form-horizontal" @submit.prevent="saveInstitute('addInstituteFormData')" id="addInstituteFormData">
                <div class="modal-body">

                    <div class="create-form-errors">
                    </div>

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="" class="col-md-3 control-label">Name <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input name="institute_name" class="form-control input-sm" type="text" placeholder="Institute name">

                            <input type="hidden" name="institute_or_degree" value="institute">
                        </div>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default modal-close-btn" id="modal-close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>

              </form>
            </div>
        </div>
    </div>
    <!-- Add Institute modal end --> 
    <!-- Add Degrees modal start -->
    <div class="modal fade bs-example-modal-lg degreeAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalDegreeAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Degree</h4>
              </div>
              <form class="form-horizontal" @submit.prevent="saveDegree('addDegreeFormData')" id="addDegreeFormData">
                <div class="modal-body">

                    <div id="create-form-errors">
                    </div>

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="" class="col-md-3 control-label">Name <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input name="degree_name" class="form-control input-sm" type="text" placeholder="Degree name">

                            <input type="hidden" name="institute_or_degree" value="degree">
                        </div>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default modal-close-btn" id="modal-close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>

              </form>
            </div>
        </div>
    </div>
    <!-- Add Degrees modal end --> 

    <!-- Edit modal start -->
    <div class="modal fade bs-example-modal-lg dataEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalDataEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Data</h4>
              </div>
              <form class="form-horizontal department-create" @submit.prevent="updateData('updateFormData')" id="updateFormData">
                
                <div class="modal-body">

                    <div id="edit-form-errors">

                    </div>

                    {{ csrf_field() }}

                    <input type="hidden" name="hdn_id" v-model="hdn_id">

                    <div class="form-group">
                        <label for="" class="col-md-3 control-label">Name</label>
                        <div class="col-md-9">
                            <input name="ins_degree_name" v-model="ins_degree_name" class="form-control input-sm" type="text" placeholder="Write name here">

                            <input type="hidden" name="institute_or_degree" v-model="institute_or_degree" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-edit-close-btn" id="modal-edit-close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        Update Data
                    </button>
                </div>

              </form>
            </div>
        </div>
    </div>
    <!-- Edit modal end --> 
</div>
@endsection

@section('script')

<script type="text/javascript" src="{{asset('/js/instituteNdegree.js')}}"></script>

@endsection