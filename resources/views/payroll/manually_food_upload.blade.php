@extends('layouts.hrms')
@section('content')

@section('style')

    <style type="text/css">
      .name_show{
        font-size: 14px;
        font-weight: bold;
        visibility: hidden;
        position: absolute;
        background: #70ca63;
        color: #fff;
        z-index: 10000;
        padding: 5px;
        margin: -10px;
        margin-top: -40px;
      }
      .show_name:hover .name_show{
        visibility: visible;
      }
    </style>
@endsection

<section id="foodID" class="p5 pt10">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel">
        <div class="panel-heading">
            <span class="panel-title"><i class="glyphicons glyphicons-history"></i></span>
            <strong>Manually Food Allowance</strong>
        </div>

        <div class="panel-body">
            <div class="row">
              <form class="admin-form" method="post" action="{{url('/ManualFoodAllowUpload/index')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="col-md-3">
                  <div class="form-group" :class="{'has-error':errors.salary_month}">
                    <label class="control-label">Select Month : <span class="text-danger">*</span></label>
                    <input type="text" name="salary_month" v-on:mouseover="myMonthPicker" class="myMonthPicker form-control input-sm" placeholder="Month.." readonly="readonly">
                    <span v-if="errors.salary_month" class="help-block" v-text="errors.salary_month[0]"></span>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group" style="margin-top: 5px;">
                    <br>
                    <button type="submit" class="form-control input-sm btn btn-sm btn-dark">Show Result</button>
                  </div>
                </div>
              </form>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                  <div class="form-group pull-right">
                    <br>
                    <button class="btn btn-success btn-sm" v-on:click.prevent="modal_open('#manual_attendance_modal')">Upload CSV File</button>
                  </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title text-dark">
                            <b>Food Upload Report Sheet</b>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div id="showData">
                            <table class="table table-hover" id="datatable">
                                <thead>
                                    <tr class="success">
                                        <th>sl</th>
                                        <th>Employee N0.</th>
                                        <th>Employee Name</th>
                                        <th>Total Mills</th>
                                        <th>Per Mill Cost</th>
                                        <td>Total Amount</td>
                                        <td>Company Pay</td>
                                        <td>Salary Month</td>
                                    </tr>
                                </thead>
                                <tbody>
                                  @if(count($infos) > 0)
                                  <?php 
                                    $sl = 1;
                                    $total_mills = 0;
                                    $total_mill_amount = 0;
                                    $total_company_pay = 0;
                                  ?>
                                    @foreach($infos as $info)
                                      <tr>
                                        <td>{{$sl++}}</td>
                                        <td>{{$info->user->employee_no}}</td>
                                        <td>{{$info->user->fullName}}</td>
                                        <td>{{$info->user_mills}}</td>
                                        <?php 
                                          $total_mills = $total_mills + $info->user_mills;
                                        ?>
                                        <td>{{$info->per_mill_cost}}</td>
                                        <td>{{$info->total_mill_amount}}</td>
                                        <?php 
                                          $total_mill_amount = $total_mill_amount + $info->total_mill_amount;
                                        ?>
                                        <td>{{$info->company_pay}}</td>
                                        <?php 
                                          $total_company_pay = $total_company_pay + $info->company_pay;
                                        ?>
                                        <td>{{$info->salary_month}}</td>
                                      </tr>
                                    @endforeach
                                      <tr>
                                        <td colspan="3"></td>
                                        <td><b>{{ $total_mills }}</b></td>
                                        <td></td>
                                        <td><b>{{ $total_mill_amount }}</b></td>
                                        <td><b>{{ $total_company_pay }}</b></td>
                                        <td></td>
                                      </tr>
                                  @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

  


  <div id="manual_attendance_modal" style="max-width:450px" class="popup-basic mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-title">
                <i class="fa fa-rocket"></i>Manual Upload Food Allownace
            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                  <form class="admin-form" method="post" action="{{url('/ManualFoodAllowUpload/temp')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                      <div class="col-md-12">
                          <div class="form-group" :class="{'has-error':errors.date}">
                            <label class="control-label pb5">Upload CSV file: <span class="text-danger">*</span></label>

                            <label class="field prepend-icon append-button file">
                              <span class="button btn-primary">Choose CSV File</span>
                              <input type="file" required="required" class="gui-file" name="csv_file" id="file1" onChange="document.getElementById('uploader1').value = this.value;">
                              <input type="text" class="gui-input" id="uploader1" placeholder="Please Select A File">
                              <label class="field-icon">
                                <i class="fa fa-upload"></i>
                              </label>
                            </label>
                            <span class="input-footer">
                              <strong>File format must be : </strong> like demo file. <a href="{{url('/ManualFoodAllowUpload/demo')}}">Download Demo</a>
                            </span>
                              <span v-if="errors.date" class="text-danger" v-text="errors.date[0]"></span>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group" :class="{'has-error':errors.salary_month}">
                          <label class="control-label">Select Month : <span class="text-danger">*</span></label>
                          <input type="text" name="salary_month" v-on:mouseover="myMonthPicker" class="myMonthPicker form-control input-sm" placeholder="Month.." readonly="readonly">
                          <!-- <span v-if="errors.salary_month" class="help-block" v-text="errors.salary_month[0]"></span> -->
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Total TK : <span class="text-danger">*</span></label>
                          <input type="text" required="required" name="total_tk" class="form-control input-sm">
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="control-label">Company Subsidiary (%) : </label>
                          <input type="text" name="company_pay" class="form-control input-sm">
                          </select>
                        </div>
                      </div>
                    </div>

                    <hr class="short alt">

                    <div class="section row mbn">
                        <div class="col-sm-6 pull-right">
                            <p class="text-left">
                                <button type="submit" name="upload_csv" @click="test()" class="btn btn-dark btn-gradient btn-sm"><i class="fa fa-upload"></i> &nbsp; Upload CSV
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

  <script type="text/javascript" src="{{asset('js/manuallyFoodUpload.js')}}"></script>

@endsection

@endsection