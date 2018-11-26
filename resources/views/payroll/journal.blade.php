@extends('layouts.hrms')
@section('content')

@section('style')
<style type="text/css">
    .select2-container .select2-selection--single{height:32px!important}
    .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:30px!important}
    .select2-container--default .select2-selection--single .select2-selection__arrow{height:30px!important}

    .select2-container{width:100%!important;height:32px!important}
    /*.fileupload-preview img{max-width: 200px!important;}*/
</style>
@endsection

<section id="journalId" class="p5 pt10">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><i class="fa fa-money"></i></span>
            <strong>Payroll Journal Voucher</strong>
        </div>

        <div class="panel-body">
          <form v-on:submit.prevent="generateJournal">

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Branch :</label>
                  <select class="form-control input-sm" name="branch_id" v-model="branch_id">
                      <option value="0">...All Branch...</option>
                      @foreach($branches as $binfo)
                      <option value="{{$binfo->id}}">{{$binfo->branch_name}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
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

              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Unit :</label>
                  <select class="form-control input-sm" name="unit_id" v-model="unit_id">
                      <option :value="0">...All Unit...</option>
                      <option v-for="(unit,index) in units" :value="unit.id" v-text="unit.unit_name"></option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              
              <div class="col-md-4">
                <div class="form-group" :class="{'has-error':errors.salary_month}">
                  <label class="control-label">Salary Month : <span class="text-danger">*</span></label>
                  <input type="text" name="salary_month" v-on:mouseover="myMonthPicker" class="myMonthPicker form-control input-sm" placeholder="Salary Month.." readonly="readonly">
                  <span v-if="errors.salary_month" class="help-block" v-text="errors.salary_month[0]"></span>
                </div>
              </div>

              <div class="col-md-2" style="padding-top:22px!important">
                <div class="form-group">
                  <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-dark">Show</button>
                </div>
              </div>
              </form>
                <form class="form-horizontal" target="_blank" action="{{url('journal/reportPdf')}}" id="" v-show="pdf_salary_month!=0" method="POST">
                  {{ csrf_field() }}
                  
                  <input type="hidden" name="branch_id" value="0" v-model="pdf_branch_id">
                  <input type="hidden" name="department_id" value="0" v-model="pdf_department_id">
                  <input type="hidden" name="unit_id" value="0" v-model="pdf_unit_id">
                  <input type="hidden" name="salary_month" value="0" v-model="pdf_salary_month">

                  <div class="col-md-1" style="padding-top:22px!important">
                    <div class="form-group">
                      <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-info">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        PDF
                      </button>
                    </div>
                  </div>
                </form>

                <form class="form-horizontal" target="_blank" action="{{url('journal/reportXl')}}" id="" v-show="pdf_salary_month!=0" method="POST">
                  {{ csrf_field() }}
                  
                  <input type="hidden" name="branch_id" value="0" v-model="pdf_branch_id">
                  <input type="hidden" name="department_id" value="0" v-model="pdf_department_id">
                  <input type="hidden" name="unit_id" value="0" v-model="pdf_unit_id">
                  <input type="hidden" name="salary_month" value="0" v-model="pdf_salary_month">

                  <div class="col-md-1" style="padding-top:22px!important; margin-left: 10px;">
                    <div class="form-group">
                      <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-info">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        Excel
                      </button>
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
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Generate Journal Voucher </strong>
          
          <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".signAdd" style="margin-top: 12px;">Pdf Signatures</button>
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>L.F</th>
                <th>Debit Taka</th>
                <th>Credit Taka</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in final_journal">
                <td v-text="index+1"></td>
                <td>
                  <a :href="'/employee/view/'+payroll.employee_no" target="_blank">
                    <span v-text="payroll.full_name"></span> (
                    <span v-text="payroll.employee_no"></span>)
                  </a>
                </td>
                <td></td>
                <td></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
              <tr>
                <td></td>
                <td><b>Total</b></td>
                <td></td>
                <td><span v-text="final_journal.length>0?final_journal[0].total_loan:0"></span></td>
                <td><span v-text="final_journal.length>0?final_journal[0].total_loan:0"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- depAdd modal start -->
  <div class="modal fade bs-example-modal-lg signAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog" role="document">
          <form class="form-horizontal" @submit.prevent="saveEmpSign('fromData')" id="fromData">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Employee Name</h4>
                  </div>
                  <div class="modal-body">
                      <div id="create-form-errors">
                          
                      </div>
                      {{ csrf_field() }}

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-01 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp1" v-model="emp_name_one">
                          </div>

                          <label for="name" class="col-md-2 control-label">Desig.-01 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig1" v-model="emp_desig_one">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-02 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp2" v-model="emp_name_two">
                          </div>

                          <label for="name" class="col-md-2 control-label">Desig.-02 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig2" v-model="emp_desig_two">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-03 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp3" v-model="emp_name_three">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-03 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig3" v-model="emp_desig_three">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-04 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp4" v-model="emp_name_four">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-04 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig4" v-model="emp_desig_four">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-05 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp5" v-model="emp_name_five">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-05 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig5" v-model="emp_desig_five">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-06 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp6" v-model="emp_name_six">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-06 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig6" v-model="emp_desig_six">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-07 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp7" v-model="emp_name_seven">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-07 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig7" v-model="emp_desig_seven">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-08 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp8" v-model="emp_name_eight">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-08 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig8" v-model="emp_desig_eight">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="name" class="col-md-2 control-label">Emp.-09 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="emp9" v-model="emp_name_nine">
                          </div>
                          <label for="name" class="col-md-2 control-label">Desig.-09 :</label>

                          <div class="col-md-4">
                              <input id="name" type="text" class="form-control input-sm" name="desig9" v-model="emp_desig_nine">
                          </div>
                      </div>

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
  <!-- depAdd modal end -->
</section>


@section('script')

<script type="text/javascript" src="{{asset('js/journal.js')}}"></script>

@endsection

@endsection