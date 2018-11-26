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

<section id="summeryId" class="p5 pt10">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><i class="fa fa-money"></i></span>
            <strong>Salary Sheet Summery</strong>
        </div>

        <div class="panel-body">
          <form v-on:submit.prevent="generateSummery">
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
                <form class="form-horizontal" target="_blank" action="{{url('SalarySheetSummery/reportPdf')}}" id="" v-show="pdf_salary_month!=0" method="POST">
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

                <form class="form-horizontal" target="_blank" action="{{url('SalarySheetSummery/reportXl')}}" id="" v-show="pdf_salary_month!=0" method="POST">
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
          <strong>Generate Salary Sheet Summery </strong>
          
          <!-- <button type="button" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target=".signAdd" style="margin-top: 12px;">Pdf Signatures</button> -->
        </div>

        <div class="panel-body pn">
          <table class="table table-bordered">
            <thead class="bg-dark" style="color: #fff!important">
              <tr>
                <th rowspan="2">SL</th>
                <th rowspan="2" class="text-center">Particulars</th>
                <th colspan="2" class="text-center">Salary Adjust</th>
                <th colspan="2" class="text-center">Bank</th>
                <th colspan="2" class="text-center">Cash</th>
                <th colspan="2" class="text-center">Total</th>
              </tr>
              <tr v-if="final_summery[0]">
                <th v-text="final_summery[0]['only_month_prev']"></th>
                <th v-text="final_summery[0]['only_month']"></th>
                <th v-text="final_summery[0]['only_month_prev']"></th>
                <th v-text="final_summery[0]['only_month']"></th>
                <th v-text="final_summery[0]['only_month_prev']"></th>
                <th v-text="final_summery[0]['only_month']"></th>
                <th v-text="final_summery[0]['only_month_prev']"></th>
                <th v-text="final_summery[0]['only_month']"></th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(payroll, index) in final_summery">
                <td v-text="index+1"></td>
                <td v-text="payroll['dep_name']"></td>
                <td v-text="payroll['advance_salary_prev']"></td>
                <td v-text="payroll['advance_salary']"></td>
                <td v-text="payroll['bank_salary_prev']"></td>
                <td v-text="payroll['bank_salary']"></td>
                <td v-text="payroll['cash_salary_prev']"></td>
                <td v-text="payroll['cash_salary']"></td>
                <td v-text="payroll['total_prev']"></td>
                <td v-text="payroll['total']"></td>
              </tr>
              <tr v-if="final_summery[0]">
                <td colspan="2"><b>Total</b></td>
                <td v-text="final_summery[0]['total_advance_salary_prev']"></td>
                <td v-text="final_summery[0]['total_advance_salary']"></td>
                <td v-text="final_summery[0]['total_bank_prev']"></td>
                <td v-text="final_summery[0]['total_bank']"></td>
                <td v-text="final_summery[0]['total_cash_prev']"></td>
                <td v-text="final_summery[0]['total_cash']"></td>
                <td v-text="final_summery[0]['total_total_prev']"></td>
                <td v-text="final_summery[0]['total_total']"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>


@section('script')

<script type="text/javascript" src="{{asset('js/salarySheetSummery.js')}}"></script>

@endsection

@endsection