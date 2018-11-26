@extends('layouts.hrms')

@section('style')
    
@endsection

@section('content')
<div id="mainDiv">
    <!-- Begin: Content -->
    <section id="content" class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">All Company Branches</span>

                        <?php 
                          $chkUrl = \Request::segment(1);
                        ?>
                        @if(in_array($chkUrl."/add", session('userMenuShare')))
                            <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".dataAdd" style="margin-top: 12px;">Add New Branch</button>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div id="showData">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                    <tr class="success">
                                        <th>sl</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Note</th>
                                        <th>Effective Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(info,index) in branches">
                                        <td >@{{ index+1 }}</td>
                                        <td>@{{ info.branch_name }}</td>
                                        <td>@{{ info.branch_email }}</td>
                                        <td>@{{ info.branch_mobile }} , @{{info.branch_phone}}</td>
                                        <td>@{{ info.branch_location }}</td>
                                        <td>@{{ info.branch_description }}</td>
                                        <td>@{{ info.branch_effective_date }}</td>
                                        <td>@{{ info.branch_status==1?"Active":"Inactive" }}</td>
                                        <td>
                                            @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                                <button type="button" @click="editData(info.id, index)" class="btn btn-sm btn-primary edit-btn" data-toggle="modal" data-target=".dataEdit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                            @if(in_array($chkUrl."/delete", session('userMenuShare')))
                                                <button type="button" @click="deleteData(info.id, index)" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
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
    <div class="modal fade bs-example-modal-lg dataAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalDataAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add New Branch</h4>
                </div>
                <form class="form-horizontal" @submit.prevent="saveBranch('addFormData')" id="addFormData">
                    <div class="modal-body">

                        <div id="create-form-errors">
                        </div>

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="branch_name" class="col-md-3 control-label">Branch Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input name="branch_name" class="form-control input-sm" v-model="branch_name" v-validate:branch_name.initial="'required'" :class="{'input': true, 'is-danger': errors.has('branch_name') }" data-vv-as="branch name" type="text" placeholder="Branch Name">
                                <div v-show="errors.has('branch_name')" class="help text-danger">
                                    <i v-show="errors.has('branch_name')" class="fa fa-warning"></i> 
                                    @{{ errors.first('branch_name') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch_email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input name="branch_email" class="form-control input-sm" v-model="branch_email" type="email" placeholder="Branch Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch_mobile" class="col-md-3 control-label">Mobile <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input name="branch_mobile" class="form-control input-sm" v-model="branch_mobile" v-validate:branch_mobile.initial="'required'" :class="{'input': true, 'is-danger': errors.has('branch_mobile') }" data-vv-as="mobile" type="text" placeholder="Branch Mobile Number">
                                <div v-show="errors.has('branch_mobile')" class="help text-danger">
                                    <i v-show="errors.has('branch_mobile')" class="fa fa-warning"></i> 
                                    @{{ errors.first('branch_mobile') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch_phone" class="col-md-3 control-label">Phone</label>
                            <div class="col-md-9">
                                <input name="branch_phone" class="form-control input-sm" v-model="branch_phone" type="text" placeholder="Branch Phone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch_location" class="col-md-3 control-label">Location <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="branch_location" class="form-control input-sm" v-model="branch_location" data-vv-as="branch location" placeholder="Branch Location"></textarea>
                                <div v-show="errors.has('branch_location')" class="help text-danger">
                                    <i v-show="errors.has('branch_location')" class="fa fa-warning"></i> 
                                    @{{ errors.first('branch_location') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="branch_effective_date" class="col-md-3 control-label">Effective Date</label>
                            <div class="col-md-9">
                                <input type="text" name="branch_effective_date" class="gui-input datepicker form-control input-sm" placeholder="Select Effective Date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Note</label>
                            <div class="col-md-9">
                                <textarea name="branch_description" class="form-control input-sm" placeholder="Write note"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="radio-custom radio-success mb5">
                                            <input type="radio" name="branch_status" id="active" v-model="branch_status" value="1">
                                            <label for="active">Active</label>
                                        </div>    
                                    </div>
                                    <div class="col-md-4">
                                        <div class="radio-custom radio-danger mb5">
                                            <input type="radio" name="branch_status" id="inactive" v-model="branch_status" value="0">
                                            <label for="inactive">Inactive</label>
                                        </div>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close-btn" id="modal-close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Branch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- unitAdd modal end --> 

    <!-- salary Info Edit modal start -->
    <div class="modal fade bs-example-modal-lg dataEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalDataEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Branch</h4>
                </div>
                
                <form class="form-horizontal" @submit.prevent="updateData('updateFormData')" id="updateFormData">
                    <div class="modal-body">

                        <div id="edit-form-errors">
                        </div>

                        {{ csrf_field() }}
                        <input type="hidden" name="hdn_id" data-vv-as="id" v-model="hdn_id">

                        <div class="form-group">
                            <label for="edit_branch_name" class="col-md-3 control-label">Branch Name</label>
                            <div class="col-md-9">
                                <input name="edit_branch_name" class="form-control input-sm" v-model="edit_branch_name" v-validate:edit_branch_name.initial="'required'" :class="{'input': true, 'is-danger': errors.has('edit_branch_name') }" data-vv-as="branch name" type="text" placeholder="Branch Name">
                                <div v-show="errors.has('edit_branch_name')" class="help text-danger">
                                    <i v-show="errors.has('edit_branch_name')" class="fa fa-warning"></i> 
                                    @{{ errors.first('edit_branch_name') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_branch_email" class="col-md-3 control-label">Email</label>
                            <div class="col-md-9">
                                <input name="edit_branch_email" class="form-control input-sm" v-model="edit_branch_email" type="email" placeholder="Branch Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_branch_mobile" class="col-md-3 control-label">Mobile</label>
                            <div class="col-md-9">
                                <input name="edit_branch_mobile" class="form-control input-sm" v-model="edit_branch_mobile" v-validate:edit_branch_mobile.initial="'required'" :class="{'input': true, 'is-danger': errors.has('edit_branch_mobile') }" data-vv-as="mobile" type="text" placeholder="Branch Mobile Number">
                                <div v-show="errors.has('edit_branch_mobile')" class="help text-danger">
                                    <i v-show="errors.has('edit_branch_mobile')" class="fa fa-warning"></i> 
                                    @{{ errors.first('edit_branch_mobile') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_branch_phone" class="col-md-3 control-label">Phone</label>
                            <div class="col-md-9">
                                <input name="edit_branch_phone" class="form-control input-sm" v-model="edit_branch_phone" type="text" placeholder="Branch Phone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_branch_location" class="col-md-3 control-label">Location</label>
                            <div class="col-md-9">
                                <textarea name="edit_branch_location" class="form-control input-sm" v-model="edit_branch_location" data-vv-as="branch location" placeholder="Branch Location"></textarea>
                                <div v-show="errors.has('edit_branch_location')" class="help text-danger">
                                    <i v-show="errors.has('edit_branch_location')" class="fa fa-warning"></i> 
                                    @{{ errors.first('edit_branch_location') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_branch_effective_date" class="col-md-3 control-label">Effective Date</label>
                            <div class="col-md-9">
                                <input type="text" name="edit_branch_effective_date" class="gui-input datepicker form-control input-sm" v-model="edit_branch_effective_date" placeholder="Select Effective Date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-3 control-label">Note</label>
                            <div class="col-md-9">
                                <textarea name="edit_branch_description" v-model="edit_branch_description" class="form-control input-sm" placeholder="Write note"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="radio-custom radio-success mb5">
                                            <input type="radio" name="edit_branch_status" id="edit_active" v-model="edit_branch_status" value="1">
                                            <label for="edit_active">Active</label>
                                        </div>    
                                    </div>
                                    <div class="col-md-4">
                                        <div class="radio-custom radio-danger mb5">
                                            <input type="radio" name="edit_branch_status" id="edit_inactive" v-model="edit_branch_status" value="0">
                                            <label for="edit_inactive">Inactive</label>
                                        </div>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default modal-close-btn" id="modal-edit-close-btn" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Branch</button>
                    </div>
                </form>
              
            </div>
        </div>
    </div>
    <!-- salary Info Edit modal end --> 
</div>
@endsection

@section('script')

<script src="{{asset('js/branch.js')}}"></script>

@endsection
