@extends('layouts.hrms')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <style type="text/css" media="screen">
        .sm-height {
            padding: 4px;
        }

        .btn-custom{
            color: #ffffff;
            text-decoration: none;
        }

        .btn-custom:hover{
            color: #ffffff;
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
<div id="foodUploadPrev">
    <!-- Begin: Content -->
    <section id="content" class="">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title text-danger">
                            <b>Manually Food Upload Preview Sheet</b>
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
                                        <td>Company Pay({{$company_pay}}%)</td>
                                        <td>Salary Month</td>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php 
                                    $sl=1;
                                    $total_mill_cost = 0; 
                                    $user_mill_cost = 0;
                                    $total_company_pay = 0;
                                    $final_ary = [];
                                  ?>
                                  @if(Session::has('final_ary'))
                                    <?php Session::forget('final_ary'); ?>
                                  @endif

                                  @if(count($users_ary) > 0)
                                    @foreach($users_ary as $user)
                                      <tr>
                                        <td>{{$sl++}}</td>
                                        <td>{{$user['employee_no']}}</td>
                                        <td>{{$user['employee_name']}}</td>
                                        <td>{{$user['employee_mill']}}</td>
                                        <td>{{$per_mill_cost}}</td>
                                        <td>
                                          <?php 
                                            $user_mill_cost = round($user['employee_mill'] * $per_mill_cost);
                                            $total_mill_cost = $total_mill_cost + $user_mill_cost;
                                            echo $user_mill_cost;
                                          ?>
                                        </td>
                                        <td>
                                          <?php 
                                            if(empty($company_pay) || $company_pay < 1){
                                              $companyPay = 0;
                                            }
                                            else{
                                              $companyPay = round(($company_pay * $user_mill_cost)/100);
                                            }
                                            echo $companyPay;
                                            $total_company_pay = $total_company_pay + $companyPay;
                                          ?>
                                        </td>
                                        <td>
                                          {{$salary_month}}
                                        </td>
                                        <?php 
                                            $final_ary[] = [
                                                'user_id'    => $user['user_id'],
                                                'user_mills' => (float)$user['employee_mill'],
                                                'per_mill_cost' => (float)$per_mill_cost,
                                                'total_mill_amount' => (int)$user_mill_cost,
                                                'company_pay' => (int)$companyPay,
                                                'salary_month' => $salary_month,
                                                'created_by' => Auth::user()->id,
                                            ];
                                        ?>
                                      </tr>
                                    @endforeach 
                                    <tr>
                                      <td></td>
                                      <td><b>Total Amount</b></td>
                                      <td></td>
                                      <td><b>{{$total_mills}}</b></td>
                                      <td></td>
                                      <td><b>{{$total_mill_cost}}</b></td>
                                      <td><b>{{$total_company_pay}}</b></td>
                                      <td></td>
                                    </tr>

                                    <?php 
                                      Session::put('final_ary', $final_ary);
                                    ?>
                                    <tr>
                                      <td colspan="7"></td>
                                      <td>
                                        <a class="btn btn-danger btn-sm" href="{{url('ManualFoodAllowUpload/index')}}">Cancel</a>
                                        <button class="btn btn-success btn-sm" @click="processData()">Process</button>
                                      </td>
                                    </tr>
                                  @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End: Content -->   
</div>

@section('script')
  <script type="text/javascript">
    function close_window(){

        swal({
          title: "Are you sure?",
          text: "Window will be closed!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: '#df5640',
          confirmButtonText: "Close",
          closeOnConfirm: false
        },
        function(){
            close();
        });
    }

    new Vue({
      el: '#foodUploadPrev',

      methods:{
          processData: function(){

            swal({
              title: "Are you sure?",
              text: "After save.. Data will not be editable.. ",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: '#df5640',
              confirmButtonText: "Yes, process data!",
              closeOnConfirm: false
            },
            function(){
              
                axios.get("/ManualFoodAllowUpload/processData",{
          
                })
                .then(response => { 
                   
                  swal({
                    title: "Success !",
                    text: "Allownace added successfully.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Done",
                    closeOnConfirm: false
                  },
                  function(){
                      // location.href=location.href;
                      window.location = '{{url("ManualFoodAllowUpload/index")}}';
                  });
                })
                .catch( (error) => {
                    var errors = error.response.data;
                    console.log(error);
                });
            });
          }
      },

    });
  </script>
@endsection

@endsection