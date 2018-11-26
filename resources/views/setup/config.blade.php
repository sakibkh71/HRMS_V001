@extends('layouts.setup')

@section('style')
    <style>
      
      .wizard .steps .fa,
      .wizard .steps .glyphicon,
      .wizard .steps .glyphicon {
        display: none;
      }
      .error{
        color: red !important;
      }
      .form-control{
        color: #626262 !important;
      }
    </style>
@endsection

@section('content')

    <div class="row main-div">
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Config Application</div>

                <?php $msgs = ['success','danger']; foreach($msgs as $msg){ if(Session::has($msg)){?>
                <div class="alert alert-{{$msg}} alert-dismissible" role="alert" style="margin-top:10px">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ucfirst($msg)}}!</strong> {{Session::get($msg)}}
                </div>
                <?php } }?>
                <div class="panel-body">
                    <div class="admin-form">

                        <div id="form-errors">
                            
                        </div>

                        <form method="post" action="/config" id="form-wizard">
                            <div class="wizard steps-bg clearfix steps-justified steps-show-icons">
                            <!-- Wizard step 1::  steps-left-->
                            <h4 class="wizard-section-title">
                                <i class="fa fa-user pr5"></i> Details</h4>
                            <section class="wizard-section">
                                <div class="section">
                                    <input id="company_name" type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name" required autofocus>
                                </div>

                                <div class="section">
                                    <input id="company_code" type="text" class="form-control" name="company_code" value="{{ old('company_code') }}" placeholder="Company Code">
                                </div>

                                <div class="section">
                                    <select id="package_name" name="package_name" class="form-control package_name" required="required">
                                        <option value="">Select Package</option>
                                        @foreach($packages as $package)
                                            <option value="{{$package->id}}" @if(old('package_name') == $package->id){{"selected"}}@endif>{{$package->package_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="section">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required="required">
                                </div>

                                <div class="section">
                                    <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required="required">
                                </div>

                                <div class="section">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address" required="required">
                                </div>

                                <div class="section">
                                    <input id="mobile_number" type="text" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number') }}" required="required">
                                </div>

                                <div class="section">
                                    <input id="password" type="password" class="form-control" placeholder="Password" name="password" required="required">
                                </div>

                                <div class="section">
                                    <input id="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required="required">
                                </div>

                                <div class="section">
                                    <textarea id="company_address" class="form-control" name="company_address" placeholder="Company Address" required="required">{{ old('company_address') }}</textarea>
                                </div>

                            </section>
                            <!-- Wizard step 2 -->
                            <h4 class="wizard-section-title">
                                <i class="fa fa-dollar pr5"></i> Payment</h4>
                            <section class="wizard-section" id="payment_step">
                                <div class="section">
                                    <div class="section">
                                        <label for="">Package Amount(BDT)</label><br>
                                        <input id="disable_package_amount" type="text" class="form-control" name="package_amount" value="" disabled>

                                        <label for="">Package Duration(month)</label><br>
                                        <input id="disable_package_months" type="text" class="form-control" name="package_duration" value="" required="required" disabled>
                                    </div>
                                    <div class="section">
                                        <label for="">Select Payment Type</label><br>
                                        <select id="package_type" name="package_type" class="form-control package_type" required="required">
                                            <option value="">Select Type</option>
                                            <option value="1">Credit or Debit Card</option>
                                            <option value="2">Internet Banking</option>
                                        </select>
                                    </div>
                                    
                                    <div class="section credit_or_debit_div">
                                        <label for="">Credit or Debit Card</label><br>
                                        <select id="credit_or_debit" name="credit_or_debit" class="form-control credit_or_debit">
                                            <option value="">Select Bank</option>
                                            <option value="city_amex">American Express</option>
                                            <option value="DBBL Nexaus Card">DBBL Nexus</option>
                                            <option value="MasterCard">MasterCard (via BRAC gateway)</option>
                                            <option value="city_master">MasterCard (via City Bank Gateway)</option>
                                            <option value="MasterCard_Dutch">MasterCard (via Dutch-Bangla gateway)</option>
                                        </select>
                                    </div>

                                    <div class="section internet_banking_div">
                                        <label for="">Internet Banking</label><br>
                                        <select id="internet_banking" name="internet_banking" class="form-control internet_banking">
                                            <option value="">Select Bank</option>
                                            <option value="bankasia" >Bank Asia Internet Banking</option>
                                            <option value="city" >City Touch Internet Banking</option>
                                            <option value="dbblmobilebanking" percentage="2">Rocket - DBBL Mobile Banking</option>
                                        </select>
                                    </div>
                                </div>
                            </section>
                            
                            <!-- Wizard step 3 -->
                            <h4 class="wizard-section-title">
                              <i class="fa fa-shopping-cart pr5"></i> Checkout
                            </h4>
                            <section class="wizard-section">
                              <div class="section">
                                <p>
                                    You would be redirected to a third party payment gateway where you can pay with your internet banking accounts. Your payment transactions are 100% secure. On successful payment, you would get a confirmed ticket.
                                </p>
                              </div>
                              <!-- end section -->
                            </section>

                            </div>
                            <!-- End: Wizard -->
                        </form>
                        <!-- End Account2 Form -->
                    </div> 
                </div>
            </div>
        </div>
    </div>

    {{-- <div align="center" style="margin-top: 60px;" class="loader-cls">
       <img src="{{URL::asset("/img/ripple.gif")}}"> 
    </div> --}}

@endsection

@section('script')

    <script src="{{asset('admin-tools/admin-forms/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('admin-tools/admin-forms/js/jquery.steps.min.js')}}"></script>

  <script type="text/javascript">

    jQuery(document).ready(function() {

    $('.credit_or_debit_div').hide();    
    $('.internet_banking_div').hide(); 
    $('#credit_or_debit').attr('required', true);
    $('#internet_banking').attr('required', true);

    "use strict";

    // Init Theme Core    
    Core.init();

    // Init Demo JS     
    Demo.init();

    // Form Wizard 
    var form = $("#form-wizard");
    form.validate({
      errorPlacement: function errorPlacement(error, element) {
        element.before(error);
      },
      rules: {
        password_confirmation: {
          equalTo: "#password"
        }
      },
      messages:{
        company_name: "Company name is required.",
        package_name: "Package name is required.",
        first_name: "First name is required.",
        last_name: "Last name is required.",
        email: "Email address is required.",
        mobile_number: "Mobile number is required.",
        password: "Password is required.",
        company_address: "Company address is required.",
      }
    });
    form.children(".wizard").steps({
      headerTag: ".wizard-section-title",
      bodyTag: ".wizard-section",
      onStepChanging: function(event, currentIndex, newIndex) {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
      },
      onFinishing: function(event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
      },
      onFinished: function(event, currentIndex) {

        //***loading Overlay
        $.LoadingOverlay("show");
        
        //************* Post form ************
            $.ajax({
                url: '{{url("config")}}',
                type: 'POST',
                data: form.serialize(),
            })
            .done(function(data) {

                $.LoadingOverlay("hide"); //***loading Overlay

                swal({
                    title: "Success!",
                    text: "User added successfully !",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Done",
                    closeOnConfirm: false
                },
                function(){
                    location.href=location.href;
                });
            })
            .fail(function(data) {

                $.LoadingOverlay("hide");  //***loading Overlay

                var errors = data.responseJSON;

                errorsHtml = '<div class="alert alert-danger"><ul>';

                $.each( errors , function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                });
                errorsHtml += '</ul></di>';
                    
                $( '#form-errors' ).html( errorsHtml );
            });
        
      }
    });

    // Demo Wizard Functionality
    var formWizard = $('.wizard');
    var formSteps = formWizard.find('.steps');

    $('.wizard-options .holder-style').on('click', function(e) {
      e.preventDefault();

      var stepStyle = $(this).data('steps-style');

      var stepRight = $('.holder-style[data-steps-style="steps-right"');
      var stepLeft = $('.holder-style[data-steps-style="steps-left"');
      var stepJustified = $('.holder-style[data-steps-style="steps-justified"');

      if (stepStyle === "steps-left") {
        stepRight.removeClass('holder-active');
        stepJustified.removeClass('holder-active');
        formWizard.removeClass('steps-right steps-justified');
      }
      if (stepStyle === "steps-right") {
        stepLeft.removeClass('holder-active');
        stepJustified.removeClass('holder-active');
        formWizard.removeClass('steps-left steps-justified');
      }
      if (stepStyle === "steps-justified") {
        stepLeft.removeClass('holder-active');
        stepRight.removeClass('holder-active');
        formWizard.removeClass('steps-left steps-right');
      }

      // Assign new style 
      if ($(this).hasClass('holder-active')) {
        formWizard.removeClass(stepStyle);
      } else {
        formWizard.addClass(stepStyle);
      }

      // Assign new active holder
      $(this).toggleClass('holder-active');
    });

    });


    $('.wizard').on('change','.package_type',function() {
        
        //alert($(this).val());

        if($(this).val() == 1){
            $('.credit_or_debit_div').show();    
            $('.internet_banking_div').hide(); 
            $('#credit_or_debit').attr('required', true);
            $('#internet_banking').removeAttr('required');
        }
        else if($(this).val() == 2){
            $('.credit_or_debit_div').hide();    
            $('.internet_banking_div').show();
            $('#credit_or_debit').removeAttr('required');
            $('#internet_banking').attr('required', true);
        }
        else{
            $('.credit_or_debit_div').hide();    
            $('.internet_banking_div').hide();   
        }
    });

    $('.wizard').on('change','.package_name',function() {

        var package_name = $(this).val();

        $.ajax({
            url: '{{url("config/get_package_info")}}',
            type: 'POST',
            data: {package_name: package_name},
        })
        .done(function(data) {

            if(data != 'error'){
                $('#disable_package_amount').val(data['price']);
                $('#disable_package_months').val(data['duration']);
            }
        });
        
    });
  </script>
@endsection