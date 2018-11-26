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

<section id="payroll" class="p5 pt10">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><i class="fa fa-money"></i></span>
            <strong>Employee Salary</strong>
        </div>

        <div class="panel-body">
            <form v-on:submit.prevent="generateSalary">
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
                  <div class="form-group">
                      <label class="control-label">Employee : </label>
                      <select class="form-control select-sm input-sm" name="user_id">
                          <option :value="0">...All Employee...</option>
                          <option v-for="(user,index) in users" :value="user.id" v-text="user.fullname+' - ('+user.employee_no+' )'"></option>
                      </select>
                  </div>
                </div>


                <div class="col-md-2">
                  <div class="form-group" :class="{'has-error':errors.salary_month}">
                    <label class="control-label">Salary Month : <span class="text-danger">*</span></label>
                    <input type="text" name="salary_month" v-on:mouseover="myMonthPicker" class="myMonthPicker form-control input-sm" placeholder="Salary Month.." readonly="readonly">
                    <span v-if="errors.salary_month" class="help-block" v-text="errors.salary_month[0]"></span>
                  </div>
                </div>
              
                <div class="col-md-2" style="padding-top:22px!important">
                  <div class="form-group">
                    <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-dark">Show Salaries</button>
                  </div>
                </div>
                </form>

                <form class="form-horizontal" v-show="pdf_salary_month != 0" target="_blank" action="{{url('payrollReport/depSalarySheet')}}" id="" method="POST">
                  {{ csrf_field() }}
                  <input type="hidden" name="branch_id" value="0" v-model="pdf_branch_id">
                  <input type="hidden" name="department_id" value="0" v-model="pdf_department_id">
                  <input type="hidden" name="unit_id" value="0" v-model="pdf_unit_id">
                  <input type="hidden" name="user_id" value="0" v-model="pdf_user_id">
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

                <form class="form-horizontal" v-show="pdf_salary_month != 0" target="_blank" action="{{url('payrollReport/depSalarySheetExl')}}" id="" method="POST">
                  {{ csrf_field() }}
                  <input type="hidden" name="branch_id" value="0" v-model="pdf_branch_id">
                  <input type="hidden" name="department_id" value="0" v-model="pdf_department_id">
                  <input type="hidden" name="unit_id" value="0" v-model="pdf_unit_id">
                  <input type="hidden" name="user_id" value="0" v-model="pdf_user_id">
                  <input type="hidden" name="salary_month" value="0" v-model="pdf_salary_month">

                  <div class="col-md-1" style="padding-top:22px!important; margin-left: 10px;">
                    <div class="form-group">
                      <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-primary">
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
          <strong>Salary Sheet </strong>

          <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".signAdd" style="margin-top: 12px;">Pdf Signatures</button>
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered" id="datatableCall">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee</th>
                <th>Working<br/> Days</th>
                <th style="background-color: green;">Basic</th>
                <th style="background-color: green; width: 130px;">Allowance</th>
                <th>Gross</th>
                <th style="background-color: #FF0000; width: 130px;">Deduction</th>
                <th style="background-color: #FF0000;">Total Ded.</th>
                <th>Net. Salary</th>
                <th>Bank</th>
                <th>Cash</th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in payRolls">
                <td v-text="index+1"></td>
                <td>
                  <a :href="'/employee/view/'+payroll.employee_no" target="_blank">
                    <span v-text="payroll.full_name"></span><br>
                    <span v-text="payroll.employee_no"></span>
                  </a>
                </td>
                <td v-text="payroll.salary_days"></td>
                <td v-text="payroll.basic_salary"></td>
                <td>
                  <span v-for="info in payroll.allowances">
                    @{{info.name}} : @{{info.amount}}<br/>
                  </span>
                </td>
                <td v-text="payroll.gross_salary"></td>
                <td>
                  <span v-for="info in payroll.deductions">
                    @{{info.name}} : @{{info.amount}}<br/>
                  </span>
                </td>
                <td v-text="payroll.total_deduction"></td>
                <td v-text="payroll.net_salary"></td>
                <td v-text="payroll.salary"></td>
                <td v-text="payroll.salary_in_cash"></td>
                <td>
                  <!-- <div class="btn-group mt5">
                    <a class="btn btn-xs btn-primary" v-on:click.prevent="paySlip(index, payroll.salary_month_format)">
                      <i class="fa fa-print" aria-hidden="true"></i>
                    </a>
                  </div> -->
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="payslip_modal" class="popup-basic mfp-with-anim mfp-hide" style="min-width: 980px!important">
    <div class="panel">
      <div class="panel-body" id="payslip">
          <div class="row">
            <div class="col-md-12">
              <h1 class="text-center">{{Session('company_name')}}</h3>
              <h3 class="text-center">Location</h4>
              <h2 class="text-center">Pay Slip for the month of <span v-text="payRoll.salary_month_format"></span></h2>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td><strong>Employee ID: </strong></td>
                    <td v-text="payRoll.employee_no"></td>
                    <!-- <td><strong>Date of Joining: </strong></td>
                    <td v-text="payRoll.joining_date"></td> -->
                  </tr>
                  <tr>
                    <td><strong>Name: </strong></td>
                    <td colspan="4" v-text="payRoll.full_name"></td>
                  </tr>
                  <tr>
                    <td><strong>Department: </strong></td>
                    <td v-text="payRoll.department"></td>
                    <td><strong>Designation: </strong></td>
                    <td v-text="payRoll.designation"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="row mt20">
            <div class="col-md-12">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">Earnings</th>
                    <th class="text-center">Deductions</th>
                  </tr>
                </thead>

                <tbody>
                  <tr>
                    <td style="padding: 0px!important; vertical-align:top!important">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th class="text-center">Descriptions</th>
                            <th class="text-center">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Basic</td>
                            <td class="text-center" v-text="payRoll.basic_salary"></td>
                          </tr>
                          <tr>
                            <td>Salary In Cash</td>
                            <td class="text-center" v-text="payRoll.salary_in_cash"></td>
                          </tr>
                          <tr v-for="(allowance, index) in payRoll.allowances">
                            <td v-text="allowance.name"></td>
                            <td class="text-center" v-text="allowance.amount"></td>
                          </tr>
                          <!-- <tr>
                            <td>Over Time</td>
                            <td class="text-center" v-text="payRoll.overtime_amount"></td>
                          </tr> -->
                        </tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">Gross Earning</th>
                            <th class="text-center" v-text="paySlipGrossEarningCalculation(payRoll.basic_salary,payRoll.salary_in_cash,payRoll.total_allowance, payRoll.overtime_amount)"></th>

                          </tr>
                        </tfoot>
                      </table>
                    </td>

                    <td style="padding: 0px!important; vertical-align:top!important">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th class="text-center">Descriptions</th>
                            <th class="text-center">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(deduction, index) in payRoll.deductions">
                            <td v-text="deduction.name"></td>
                            <td class="text-center" v-text="deduction.amount"></td>
                          </tr>
                          <tr v-for="row in allowance_row_diff">
                            <td style="visibility: hidden;">-</td>
                            <td style="visibility: hidden;">-</td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">Gross Deduction</th>
                            <th class="text-center" v-text="payRoll.total_deduction"></th>
                          </tr>
                        </tfoot>
                      </table>
                    </td>
                  </tr>
                </tbody>

                <tfoot>
                  <tr>
                    <th colspan="2" class="text-center">
                      Net Pay : <span v-text="payRoll.total_salary"></span>
                      ( <span v-text="convertNumberToWords(payRoll.total_salary)"></span> )
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          
          <hr class="short alt">
          <div class="section row mbn" id="payslip_button">
            <div class="col-sm-2 pull-right">
                <p class="text-left">
                    <button type="submit" v-on:click.prevent="PrintElem('payslip')" class="btn btn-dark btn-gradient dark btn-block">
                            <i class="fa fa-print pr5"></i> &nbsp; Print PaySlip
                    </button>
                </p>
            </div>
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

<script type="text/javascript" src="{{asset('js/payroll_report.js')}}"></script>

@endsection

@endsection