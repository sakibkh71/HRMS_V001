@extends('layouts.hrms')
@section('content')

@section('style')
    <!-- Admin Forms CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin-tools/admin-forms/css/admin-forms.css')}}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/magnific/magnific-popup.css')}}">
@endsection

<section class="animated fadeIn p10" id="bank_list">
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            <span class="glyphicon glyphicon-tasks"></span>Bank Information

            <?php 
              $chkUrl = \Request::segment(1);
            ?>
            @if(in_array($chkUrl."/add", session('userMenuShare')))
            <span class="pull-right">
              <a v-on:click="modal_open('#bank_modal'),errors=[],bank=[]" class="btn btn-sm btn-dark btn-gradient dark"><span class="glyphicons glyphicon-pencil"></span> &nbsp; Add Bank</a>
            </span>
            @endif
        </div>
    </div>
    <div class="panel-body pn">
        <table class="table table-striped table-hover" id="datatableCall" cellspacing="0" width="100%">
            <thead>
            <tr class="bg-dark">
                <th>SL:</th>
                <th>Bank Code</th>
                <th>Bank Name</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tfoot>
            <tr class="bg-dark" style="background: #f2f2f2!important">
                <th>SL:</th>
                <th>Bank Code</th>
                <th>Bank Name</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <th>Action</th>
            </tr>
            </tfoot>
            <tbody v-if="banks !=''">
                <tr v-for="(bank,index) in banks">
                   <td>@{{index+1}}</td>
                   <td>@{{bank.bank_code}}</td>
                   <td>@{{bank.bank_name}}</td>
                   <td>
                     <div class="btn-group pt5">
                         <a class="btn btn-sm" :class="(bank.status == 0)?'text-primary':'text-danger'" v-on:click="changeStatus($event,bank.id)" :status="bank.status" v-text="(bank.status == 0)?'Active':'Inactive'"></a>
                       </div>
                   </td>
                   <td>@{{bank.created_at}}</td>
                   <td>@{{bank.updated_at}}</td>
                   <td>
                      @if(in_array($chkUrl."/edit", session('userMenuShare')))
                       <div class="btn-group">
                           <a v-on:click="editBank(bank.id,'#bank_modal'),bank=[],errors =[]" class="btn btn-sm btn-primary"><i class="glyphicons glyphicons-pencil"></i>
                           </a>
                       </div>
                      @endif
                      @if(in_array($chkUrl."/delete", session('userMenuShare')))
                       <div class="btn-group">
                           <a v-on:click="deleteBank(bank.id,index)" class="btn btn-sm btn-danger">
                               <i class="glyphicons glyphicons-bin"></i>
                           </a>
                       </div>
                      @endif
                   </td>
                </tr>
            </tbody>

            <tbody v-else>
              <tr>
                <td colspan="7">No data available</td>
              </tr>
            </tbody>
            
        </table>
    </div>
</div>

<div id="bank_modal" style="max-width:400px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title" v-if="bank !=''">
                <i class="fa fa-rocket"></i>Edit Bank
            </span>
            <span class="panel-title" v-else>
                <i class="fa fa-rocket"></i>Add New Bank
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form v-if="bank ==''" id="bank_modal_form" method="post" v-on:submit.prevent="addBank">
                        <div class="row">
                          <div class="col-md-12">
                              <div class="form-group" :class="{'has-error':errors.bank_code}">
                                  <label class="control-label">Bank Code : <span class="text-danger">*</span></label>
                                  <input type="text" name="bank_code" class="form-control input-sm">
                                  <span v-if="errors.bank_code" class="text-danger" v-text="errors.bank_code[0]"></span>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group" :class="{'has-error':errors.bank_name}">
                                  <label class="control-label">Bank Name : <span class="text-danger">*</span></label>
                                  <input type="text" name="bank_name" class="form-control input-sm">
                                  <span v-if="errors.bank_name" class="text-danger" v-text="errors.bank_name[0]"></span>
                              </div>
                          </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-6 pull-right">
                                <p class="text-left">
                                    <button type="submit" name="add_bank" class="btn btn-dark btn-gradient dark btn-block">   <span class="glyphicons glyphicons-ok_2"></span> &nbsp; Add Bank
                                    </button>
                                </p>
                            </div>
                        </div>
                    </form>

                    <form v-else id="bank_modal_form" method="post" v-on:submit.prevent="updateBank">
                        <input v-if="bank.id" type="hidden" name="id" :value="bank.id">

                        <div class="row">
                          <div class="col-md-12">
                              <div class="form-group" :class="{'has-error':errors.bank_code}">
                                  <label class="control-label">Bank Code : <span class="text-danger">*</span></label>
                                  <input type="text" name="bank_code" class="form-control input-sm" v-model="bank.bank_code">
                                  <span v-if="errors.bank_code" class="text-danger" v-text="errors.bank_code[0]"></span>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group" :class="{'has-error':errors.bank_name}">
                                  <label class="control-label">Bank Name : <span class="text-danger">*</span></label>
                                  <input type="text" name="bank_name" class="form-control input-sm" v-model="bank.bank_name">
                                  <span v-if="errors.bank_name" class="text-danger" v-text="errors.bank_name[0]"></span>
                              </div>
                          </div>
                        </div>

                        <hr class="short alt">

                        <div class="section row mbn">
                            <div class="col-sm-6 pull-right">
                                <p class="text-left">
                                    <button type="submit" name="add_bank" class="btn btn-dark btn-gradient dark btn-block">   <span class="glyphicons glyphicons-ok_2"></span> &nbsp; Update Bank
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
</section>




@section('script')

<!-- Page Plugins -->
<script src="{{asset('vendor/plugins/magnific/jquery.magnific-popup.js')}}"></script>

<script type="text/javascript" src="{{asset('js/bank.js')}}"></script>

@endsection


@endsection