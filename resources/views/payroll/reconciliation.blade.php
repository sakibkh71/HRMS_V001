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

<section id="reconciliationId" class="p5 pt10">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><i class="fa fa-money"></i></span>
            <strong>Payroll Reconciliation</strong>
        </div>

        <div class="panel-body">
          <form v-on:submit.prevent="generateReconciliation">

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
              <!-- <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Employee : </label>
                    <select class="form-control select-sm input-sm" name="user_id">
                        <option :value="0">...All Employee...</option>
                        <option v-for="(user,index) in users" :value="user.id" v-text="user.fullname+' - ('+user.employee_no+' )'"></option>
                    </select>
                </div>
              </div> -->
              <div class="col-md-4">
                <div class="form-group" :class="{'has-error':errors.salary_month}">
                  <label class="control-label">Salary Month : <span class="text-danger">*</span></label>
                  <input type="text" name="salary_month" v-on:mouseover="myMonthPicker" class="myMonthPicker form-control input-sm" placeholder="Salary Month.." readonly="readonly">
                  <span v-if="errors.salary_month" class="help-block" v-text="errors.salary_month[0]"></span>
                </div>
              </div>

              <!-- <div class="col-md-2">
                <div class="form-group" :class="{'has-error':errors.advice_type}">
                  <label class="control-label">Advice Type : <span class="text-danger">*</span></label>
                  <select class="form-control select-sm input-sm" name="advice_type" v-model="advice_type">
                    <option value="bank">Bank</option>
                    <option value="cash">Cash</option>
                    <option value="both">Both</option>
                    <option value="">All</option>
                  </select>
                  <span v-if="errors.advice_type" class="help-block" v-text="errors.advice_type[0]"></span>
                </div>
              </div> -->

              <div class="col-md-2" style="padding-top:22px!important">
                <div class="form-group">
                  <button type="submit" class="form-control input-sm btn btn-sm btn-gradient btn-dark">Show</button>
                </div>
              </div>
              </form>
                <form class="form-horizontal" target="_blank" action="{{url('payrollReconciliation/reportPdf')}}" id="" v-show="pdf_salary_month!=0" method="POST">
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

                <form class="form-horizontal" target="_blank" action="{{url('payrollReconciliation/reportXl')}}" id="" v-show="pdf_salary_month!=0" method="POST">
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
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Reconciliation <span class="text-success">(Add)</span></strong>
        </div>
        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in reconciliationReport.normal_add">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
              <tr v-for="(payroll, index) in reconciliationReport.other_add">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Reconciliation <span class="text-success">(Incriment)</span></strong>
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in reconciliationReport.increment_add">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Reconciliation <span class="text-success">(New Join)</span></strong>
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in reconciliationReport.new_join_add">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Reconciliation <span class="text-danger">(Less)</span></strong>
        </div>
        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in reconciliationReport.normal_less">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Reconciliation <span class="text-danger">(Resign)</span></strong>
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in reconciliationReport.resign_less">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><i class="fa fa-money"></i></span>
          <strong>Reconciliation <span class="text-danger">(Other Less)</span></strong>
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th>SL</th>
                <th>Employee Name</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in reconciliationReport.other_less">
                <td v-text="index+1"></td>
                <td><span v-text="'('+payroll.emp_no+') '+payroll.name"></span></td>
                <td><span v-text="payroll.amount"></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>


@section('script')

<script type="text/javascript" src="{{asset('js/reconciliation.js')}}"></script>

@endsection

@endsection