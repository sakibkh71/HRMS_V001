@extends('layouts.hrms')

@section('style')
    <style type="text/css" media="screen">
        html, body {
          font: 13px/18px sans-serif;
        }
        select {
          min-width: 300px;
        }
    </style>
@endsection

@section('content')

<div id="mainDiv">
    <!-- Begin: Content -->
    <section id="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Promotion/Transfer</span>

                        <?php 
                          $chkUrl = \Request::segment(1);
                        ?>
                        @if(in_array($chkUrl."/add", session('userMenuShare')))
                            <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".newDataAdd" style="margin-top: 12px;">Add New Data</button>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div id="showData">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                    <tr class="success">
                                        <th>sl</th>
                                        <th>Type</th>
                                        <th>Employee Name</th>
                                        <th>Designation</th>
                                        <th>prev. Desig.</th>
                                        <th>Branch</th>
                                        <th>Unit</th>
                                        <th>Prev. Unit</th>
                                        <th>Supervisor</th>
                                        <th>Prev. Supvi.</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-show="allData">
                                    <tr v-for="(info,index) in allData">
                                        <td v-text="index+1"></td>
                                        <td v-text="info.promotion_type"></td>
                                        <td v-text="info.user.first_name +' '+ info.user.last_name"></td>
                                        <td v-text="
                                        info.current_designation.designation_name+'-('+
                                        info.current_designation.level.level_name+')-('+
                                        info.current_designation.department.department_name+')'"
                                        ></td>
                                        <td v-text="
                                        info.prev_designation.designation_name+'-('+
                                        info.prev_designation.level.level_name+')-('+
                                        info.prev_designation.department.department_name+')'"
                                        ></td>
                                        <td v-text="info.prev_branch.branch_name+' TO '+info.current_branch.branch_name"></td>
                                        <td v-text="info.current_unit.unit_name +'-( '+info.current_unit.promotion_department.department_name+')'"></td>
                                        <td v-text="
                                        info.prev_unit.unit_name+'-('+info.prev_unit.promotion_department.department_name+')'"
                                        ></td>
                                        <td>
                                            <span v-if="info.current_supervisor" v-text="info.current_supervisor.first_name+' '+info.current_supervisor.last_name"></span>
                                        </td>
                                        <td>
                                            <span v-if="info.prev_supervisor" v-text="info.prev_supervisor.first_name+' '+info.prev_supervisor.last_name"></span>
                                        </td>
                                        <td v-text="info.transfer_effective_date"></td>
                                        <td>
                                            @if(in_array($chkUrl."/edit", session('userMenuShare')))
                                                <button type="button" @click="editData(info.id, index)" class="btn btn-xs btn-primary edit-btn" data-toggle="modal" data-target=".dataEdit">
                                                <i class="fa fa-edit"></i>
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

    <!-- End: Content -->   

    <!-- Add modal start -->
    <div class="modal fade bs-example-modal-lg newDataAdd" role="dialog" aria-labelledby="myLargeModalLabel" id="modalAdd">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add new @{{formType}} info</h4>
                </div>
                <form class="form-horizontal" @submit.prevent="savePromotion('addPromotionFormData')" id="addPromotionFormData">
                    <div class="modal-body">

                        <div id="create-form-errors">
                        </div>

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="form_type" class="col-md-3 control-label">Info Type: <span
                                                                class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="form_type" v-model="formType" class="form-control input-sm">
                                    <option value="">Select Type</option>
                                    <option value="promotion">Promotion</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="user_id" class="col-md-3 control-label">Select User: <span
                                                                class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select2 name="user_id" v-model="user_id" style="
                                width: 100%;color: #555555;
                                border: 1px solid #dddddd;
                                transition: border-color ease-in-out .15s;
                                height: 30px;
                                padding: 5px 10px;
                                font-size: 12px;
                                line-height: 1.5;
                                border-radius: 2px;"
                                >
                                    <option disabled value="0">Select Single User</option>
                                    <option v-for="(info,index) in users" 
                                        :value="info.id" 
                                        v-text="info.first_name+' '+info.last_name"
                                    ></option>
                                </select2>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="from_designation_id" class="col-md-3 control-label">Current Designation:</label>
                            <div class="col-md-9">
                                <input readonly="readonly" class="form-control input-sm" v-model="from_designation" type="text">

                                <input name="from_designation_id" class="form-control input-sm" v-model="from_designation_id" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="to_designation_id" class="col-md-3 control-label">New Designation:</label>
                            <div class="col-md-9">
                                <select2 name="to_designation_id" v-model="to_designation_id" style="
                                width: 100%;color: #555555;
                                border: 1px solid #dddddd;
                                transition: border-color ease-in-out .15s;
                                height: 30px;
                                padding: 5px 10px;
                                font-size: 12px;
                                line-height: 1.5;
                                border-radius: 2px;"
                                >
                                    <option disabled value="0">Select New Designation</option>
                                    <option v-for="(info,index) in designation_select2" 
                                        :value="info.id" 
                                        v-text="info.designation_name+'-('+info.level.level_name+')-('+info.department.department_name+')'"
                                    ></option>
                                </select2>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="from_unit_id" class="col-md-3 control-label">Current Unit:</label>
                            <div class="col-md-9">
                                <input readonly="readonly" class="form-control input-sm" v-model="from_unit" type="text">

                                <input name="from_unit_id" class="form-control input-sm" v-model="from_unit_id" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="to_unit_id" class="col-md-3 control-label">New Unit:</label>
                            <div class="col-md-9">
                                <select name="to_unit_id" class="form-control input-sm">
                                    <option value="0">Select New Unit</option>
                                    <option v-for="info in unit_select2" 
                                        :value="info.id" 
                                        v-text="info.unit_name"
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="from_branch_id" class="col-md-3 control-label">Current Branch:</label>
                            <div class="col-md-9">
                                <input readonly="readonly" class="form-control input-sm" v-model="from_branch" type="text">

                                <input name="from_branch_id" class="form-control input-sm" v-model="from_branch_id" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="to_branch_id" class="col-md-3 control-label">New Branch:</label>
                            <div class="col-md-9">
                                <select name="to_branch_id" class="form-control input-sm">
                                    <option value="0">Select New Branch</option>
                                    <option v-for="info in branch_select2" 
                                        :value="info.id" 
                                        v-text="info.branch_name"
                                        v-if="info.branch_name != from_branch"
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="from_supervisor_id" class="col-md-3 control-label">Current Supervisor:</label>
                            <div class="col-md-9">
                                <input readonly="readonly" class="form-control input-sm" v-model="from_supervisor" type="text">

                                <input name="from_supervisor_id" class="form-control input-sm" v-model="from_supervisor_id" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="to_supervisor_id" class="col-md-3 control-label">New Supervisor:</label>
                            <div class="col-md-9">
                                <select name="to_supervisor_id" class="form-control input-sm">
                                    <option value="0">Select New Supervisor</option>
                                    <option v-for="info in supervisor_select2" 
                                        :value="info.id" 
                                        v-text="info.first_name+' '+info.last_name"
                                        v-if="info.id != from_supervisor_id"
                                    ></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="to_supervisor_id" class="col-md-3 control-label">Effective Date: <span
                                                                class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="effective_date" class="gui-input datepicker form-control input-sm" placeholder="Select Effective Date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="remarks" class="col-md-3 control-label">Remarks</label>
                            <div class="col-md-9">
                                <textarea name="remarks" class="form-control input-sm" placeholder="Write remarks"></textarea>
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
    <!-- Add modal end --> 

    <!-- Edit modal start /dataEdit -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalEdit">
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
                        <label for="edit_form_type" class="col-md-3 control-label">Info Type: <span
                                                            class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select name="edit_form_type" v-model="edit_formType" class="form-control input-sm">
                                <option value="">Select Type</option>
                                <option value="promotion">Promotion</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_user_id" class="col-md-3 control-label">Select User: <span
                                                            class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input disabled class="form-control input-sm" v-model="edit_user_id" type="text">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_from_designation_id" class="col-md-3 control-label">Previous Designation:</label>
                        <div class="col-md-9">
                            <input readonly="readonly" class="form-control input-sm" v-model="edit_from_designation" type="text">

                            <input name="edit_from_designation_id" class="form-control input-sm" v-model="edit_from_designation_id" type="hidden">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_to_designation_id" class="col-md-3 control-label">New Designation:</label>
                        <div class="col-md-9">
                            <select name="edit_to_designation_id" v-model="edit_to_designation_id" class="form-control input-sm" v-on:click="onClickEditDesignation">
                                <option disabled="" value="0">Select New Designation</option>
                                <option v-for="info in edit_designation_select2" 
                                    :value="info.id" 
                                    v-text="info.designation_name+'-('+info.level.level_name+')-('+info.department.department_name+')'"
                                ></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_from_unit_id" class="col-md-3 control-label">Previous Unit:</label>
                        <div class="col-md-9">
                            <input readonly="readonly" class="form-control input-sm" v-model="edit_from_unit" type="text">

                            <input name="edit_from_unit_id" class="form-control input-sm" v-model="edit_from_unit_id" type="hidden">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_to_unit_id" class="col-md-3 control-label">New Unit:</label>
                        <div class="col-md-9">
                            <select name="edit_to_unit_id" class="form-control input-sm">
                                <option value="0">Select New Unit</option>
                                <option v-for="info in edit_unit_select2" 
                                    :value="info.id" 
                                    v-text="info.unit_name"
                                    :selected="info.id==edit_to_unit_id"
                                ></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_from_branch_id" class="col-md-3 control-label">Previous Branch:</label>
                        <div class="col-md-9">
                            <input readonly="readonly" class="form-control input-sm" v-model="edit_from_branch" type="text">

                            <input name="edit_from_branch_id" class="form-control input-sm" v-model="edit_from_branch_id" type="hidden">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_to_branch_id" class="col-md-3 control-label">New Branch:</label>
                        <div class="col-md-9">
                            <select name="edit_to_branch_id" class="form-control input-sm">
                                <option value="0">Select New Branch</option>
                                <option v-for="info in edit_branch_select2" 
                                    :value="info.id" 
                                    v-text="info.branch_name"
                                    v-if="info.branch_name != from_branch"
                                    :selected="info.id==edit_to_branch_id"
                                ></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_from_supervisor_id" class="col-md-3 control-label">Previous Supervisor:</label>
                        <div class="col-md-9">
                            <input readonly="readonly" class="form-control input-sm" v-model="edit_from_supervisor" type="text">

                            <input name="edit_from_supervisor_id" class="form-control input-sm" v-model="edit_from_supervisor_id" type="hidden">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_to_supervisor_id" class="col-md-3 control-label">New Supervisor:</label>
                        <div class="col-md-9">
                            <select name="edit_to_supervisor_id" class="form-control input-sm">
                                <option value="0">Select New Supervisor</option>
                                <option v-for="info in edit_supervisor_select2" 
                                    :value="info.id" 
                                    v-text="info.first_name+' '+info.last_name"
                                    v-if="info.id != from_supervisor_id"
                                    :selected="info.id==edit_to_supervisor_id"
                                ></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_effective_date" class="col-md-3 control-label">Effective Date: <span
                                                            class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" name="edit_effective_date" class="gui-input datepicker form-control input-sm" v-model="edit_effective_date" placeholder="Select Effective Date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_remarks" class="col-md-3 control-label">Remarks</label>
                        <div class="col-md-9">
                            <textarea name="edit_remarks" v-model="edit_remarks" class="form-control input-sm" placeholder="Write remarks"></textarea>
                        </div>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default modal-edit-close-btn" id="modal-edit-close-btn" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">
                    Update Unit
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

<script src="{{asset('/js/promotion.js')}}"></script>

@endsection