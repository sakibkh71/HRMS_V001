<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <title>{{$title or "HRMS"}}</title>
    <meta name="keywords" content="iddl, hrms, afc" />
    <meta name="description" content="human resource management system">
    <meta name="author" content="IDDL">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{asset('img/logo.png')}}">
    <!-- Font CSS (Via CDN) -->
    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700'>

    <!-- Glyphicons Pro CSS(font) -->
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/glyphicons-pro/glyphicons-pro.css')}}">

    <!-- Icomoon CSS(font) -->
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/icomoon/icomoon.css')}}">

    <!-- Iconsweets CSS(font) -->
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/iconsweets/iconsweets.css')}}">

    <!-- Octicons CSS(font) -->
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/octicons/octicons.css')}}">

    <!-- Stateface CSS(font) -->
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/stateface/stateface.css')}}">

    <!-- Font Awesome CSS(font) -->
    <link rel="stylesheet" type="text/css" href="{{asset('fonts/font-awesome/font-awesome.min.css')}}">

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('admin-tools/admin-forms/css/admin-forms.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('sweet_alert/sweetalert.css')}}">

        @yield('style')

        <style type="text/css" media="screen">
            #content_wrapper{
                margin-left: 0px !important;
            }
        </style>
    </head>

<body class="blank-page">

    <!-- Start: Main -->
    <div id="main">

        @include('layouts.setup_navbar')

        <!-- Start: Content-Wrapper -->
        <section id="content_wrapper">
            <div class="container" style="margin-top: 70px;">
                @yield('content')
            </div>
        </section>
        <!-- End: Content-Wrapper -->
    </div>
    <!-- End: Main -->

<!-- BEGIN: PAGE SCRIPTS -->
<script src="{{asset('js/hrms.js')}}"></script>
<script src="{{asset('vendor/jquery/jquery-1.11.1.min.js')}}"></script>
<script src="{{asset('vendor/jquery/jquery_ui/jquery-ui.min.js')}}"></script>

<script src="{{asset('js/utility/utility.js')}}"></script>
<script src="{{asset('js/demo/demo.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('sweet_alert/sweetalert.min.js')}}"></script>

<script src="{{asset('js/loadingoverlay.min.js')}}"></script>
<script src="{{asset('js/loadingoverlay_progress.min.js')}}"></script>

<script type="text/javascript">
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery(document).ready(function() {

        "use strict";

        // Init Theme Core
        Core.init();

        // Init Demo JS
        Demo.init();

    });
</script>
<!-- END: PAGE SCRIPTS -->

@yield('script')

</body>

</html>
