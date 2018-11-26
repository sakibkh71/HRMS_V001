@extends('layouts.setup')

@section('style')
<style type="text/css" media="screen">
    a{
      color: #fff;
    }

</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Payment Details</div>
                
                <?php $msgs = ['success','danger']; foreach($msgs as $msg){ if(Session::has($msg)){?>
                <div class="alert alert-{{$msg}} alert-dismissible" role="alert" style="margin-top:10px">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ucfirst($msg)}}!</strong> {{Session::get($msg)}}
                </div>
                <?php } }?>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>sl</th>
                                <th>Package Name</th>
                                <th>Levels</th>
                                <th>Users</th>
                                <th>Sister Concern</th>
                                <th>Payment Amount</th>
                                <th>Pay. Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <?php 
                            $sl=1; 
                            $chk_sis_con = 0;
                        ?>
                        <tbody>
                        @foreach($payment_info as $payment)
                            <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $payment->package->package_name }}</td>
                                <td>{{ $payment->package->package_level_limit }}</td>
                                <td>{{ $payment->package->package_user_limit }}</td>
                                <?php 
                                    $chk_sis_con =  $payment->package->package_sister_concern_limit; 
                                ?>
                                <td>{{ $payment->package->package_sister_concern_limit }}</td>
                                <td>{{ $payment->payment_amount }} (BDT)</td>
                                <?php $paymentDate = date("Y-m-d", strtotime($payment->created_at)); ?>
                                <td>{{ $paymentDate }}</td>
                                <?php 
                                    $package_duration = $payment->package->package_duration;
                                    $formet = "+$package_duration month";
                                ?>
                                <td>
                                    {{ date('Y-m-d', strtotime($formet, strtotime($paymentDate))) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>    
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Company Info</div>
                <div class="panel-body">
                   <table class="table">
                       <tr>
                           <td><b>Company Name: </b></td>
                           <td>{{$config_info->company_name}}</td>
                       </tr>
                       <?php 
                          session(['user_id' => $user_info->id]);
                          session(['parent_id' => $config_info->id]);
                          session(['end_date' => $config_info->package_end_date]);
                          session(['sister_concern' => $chk_sis_con]);
                       ?>
                       <tr>
                           <td><b>Contact Person: </b></td>
                           <td>{{$user_info->full_name}}</td>
                       </tr>
                       <tr>
                           <td><b>Email: </b></td>
                           <td>{{$user_info->email}}</td>
                       </tr>
                       <tr>
                           <td><b>Mobile: </b></td>
                           <td>{{$user_info->mobile_number}}</td>
                       </tr>
                       <tr>
                           <td><b>Address: </b></td>
                           <td>{{$config_info->company_address}}</td>
                       </tr>
                   </table>
                </div>
            </div>
        </div>
        @if($chk_sis_con > 0)
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Sister Concern(Current Package)

                    <div type="button" class="btn btn-xs btn-success pull-right" style="margin-top: 12px;">
                        <a href="{{url('setup/concern/add')}}" title="">
                          <span class="glyphicon glyphicon-user mr5"></span>Add
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    @if(count($sister_concern) > 0)
                        <table class="table">
                          <thead>
                            <tr>
                              <th>sl</th>
                              <th>Name</th>
                              <th>Address</th>
                            </tr>
                          </thead>
                          <?php $sl=1; ?>
                          <tbody>
                            @foreach($sister_concern as $concern)
                              <tr>
                                <td>{{ $sl++ }}</td>
                                <td>{{ $concern->company_name }}</td>
                                <td>{{ $concern->company_address }}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                    @else 
                        {{ "No sister concern added yet !!!" }}
                    @endif
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                  User Modules
                  <div type="button" class="btn btn-xs btn-success pull-right" style="margin-top: 12px;" data-toggle="modal" data-target=".modalAdd">
                    <span class="fa fa-plus-circle mr5"></span>Add
                  </div>
                </div>
                <div class="panel-body">
                  <ul>
                    @foreach($hrms_modules as $info)
                      <li>{{$info->module_name}}</li>
                    @endforeach    
                  </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modalAdd modal start -->
<div class="modal fade bs-example-modal-lg modalAdd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog" role="document">
        <form class="form-horizontal department-create" method="post" action="{{url('/setup/addHrmsModule')}}" id="department-create">

          {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Extra Module</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group{{ $errors->has('new_module') ? ' has-error' : '' }}">
                        <label for="new_module" class="col-md-3 control-label">Available Module</label>

                        <input type="hidden" value="{{$config_info->database_name}}" name="user_db">  

                        <div class="col-md-9">
                              <select name="new_module" id="new_module" class="form-control input-sm">
                                <option value="">Select Module</option>
                                @foreach($all_modules as $mInfo)
                                  @unless(in_array($mInfo->id, $hrms_modules_id))
                                    <option value="{{$mInfo->id}}">{{$mInfo->module_name}}</option>
                                  @endunless
                                @endforeach
                              </select>

                            @if ($errors->has('new_module'))
                                <span class="help-block error-cls">
                                    <strong>{{ $errors->first('new_module') }}</strong>
                                    
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Module</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- modalAdd modal end -->
@endsection

@section('script')
  <script>
    $(document).ready(function() {
    
      var error_msg = $('.error-cls').text();
      if(error_msg.length > 0){
        $('.modalAdd').modal('show');
      }
    });
  </script>
@endsection 