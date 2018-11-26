<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$title or "HRMS"}}</title>
    <meta name="keywords" content="iddl, hrms, afc" />
    <meta name="description" content="human resource management system">
    <meta name="author" content="IDDL">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

    <!-- Datatables CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/datatables/media/css/dataTables.bootstrap.css')}}">

    <!-- Datatables Editor Addon CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/datatables/extensions/Editor/css/dataTables.editor.css')}}">

    <!-- Datatables ColReorder Addon CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css')}}">

    <!-- Datepicker CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/datepicker/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/colorpicker/css/bootstrap-colorpicker.min.css')}}">

    <!-- Select2 Plugin CSS  -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/select2/css/core.css')}}">

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/hrms.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/plugins/magnific/magnific-popup.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin-tools/admin-forms/css/admin-forms.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('sweet_alert/sweetalert.css')}}">


    @yield('style')

</head>

<body class="@if(isset($sidebar_hide))sb-l-m sb-l-disable-animation @endif">

<!-- Start: Main -->
<div id="main">

    <!-- Start: Header -->
    @include('layouts.hrms_navbar')
    <!-- End: Header -->

    <!-- Start: Sidebar -->
    @include('layouts.hrms_sidebar')
    <!-- End: Sidebar -->

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

        <!-- Start: Topbar-Dropdown -->
        <div id="topbar-dropmenu" class="alt">
            <div class="topbar-menu row">
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-primary light">
                        <span class="glyphicon glyphicon-inbox text-muted"></span>
                        <span class="metro-title">Messages</span>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-info light">
                        <span class="glyphicon glyphicon-user text-muted"></span>
                        <span class="metro-title">Users</span>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-success light">
                        <span class="glyphicon glyphicon-headphones text-muted"></span>
                        <span class="metro-title">Support</span>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-system light">
                        <span class="glyphicon glyphicon-facetime-video text-muted"></span>
                        <span class="metro-title">Videos</span>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-warning light">
                        <span class="fa fa-gears text-muted"></span>
                        <span class="metro-title">Settings</span>
                    </a>
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a href="#" class="metro-tile bg-alert light">
                        <span class="glyphicon glyphicon-picture text-muted"></span>
                        <span class="metro-title">Pictures</span>
                    </a>
                </div>
            </div>
        </div>
        <!-- End: Topbar-Dropdown -->

        @yield('content')

    </section>
    <!-- End: Content-Wrapper -->
</div>
<!-- End: Main -->

<!-- BEGIN: PAGE SCRIPTS -->
<script src="{{asset('js/hrms.js')}}"></script>

<script src="{{asset('vendor/jquery/jquery-1.11.1.min.js')}}"></script>
<script src="{{asset('vendor/jquery/jquery_ui/jquery-ui.min.js')}}"></script>

<!-- Page Plugins -->
<script src="{{asset('vendor/plugins/magnific/jquery.magnific-popup.js')}}"></script>

<!-- Time/Date Plugin Dependencies -->
<script src="{{asset('vendor/plugins/globalize/globalize.min.js')}}"></script>
<script src="{{asset('vendor/plugins/moment/moment.min.js')}}"></script>

<script src="{{asset('js/jquery-overlay.min.js')}}"></script>

<!-- PNotify -->
<script src="{{asset('vendor/plugins/pnotify/pnotify.js')}}"></script>

<!-- DataTable Plugin -->
<script src="{{asset('vendor/plugins/datatables/media/js/jquery.dataTables.js')}}"></script>
<script src="{{asset('vendor/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js')}}"></script>
<script src="{{asset('vendor/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('vendor/plugins/datatables/media/js/dataTables.bootstrap.js')}}"></script>

<!-- Select2 Plugin Plugin -->
<script src="{{asset('vendor/plugins/select2/select2.min.js')}}"></script>

<!-- DateRange Plugin -->
<script src="{{asset('vendor/plugins/daterange/daterangepicker.js')}}"></script>

<!-- DateTime Plugin -->
<script src="{{asset('vendor/plugins/datepicker/js/bootstrap-datetimepicker.js')}}"></script>

<script src="{{asset('js/utility/utility.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>

<script src="{{asset('sweet_alert/sweetalert.min.js')}}"></script>



<script type="text/javascript">

    jQuery(document).ready(function() {
        "use strict";
        Core.init();


        $('#datatable1').dataTable({
            "paging":   true,
            "searching": true,
            "info": true,
            "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',

        });

        $('#datatable2').dataTable({
            "paging":   false,
            "searching": true,
            "info": false,
            "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',

        });

        $('#datatable3').dataTable({
            "sDom": 't<"dt-panelfooter clearfix"ip>',
            "oTableTools": {
                "sSwfPath": "vendor/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
            }
        });

        $('#datatable4').dataTable({
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [-1]
            }],
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": "",
                    "sNext": ""
                }
            },
            "iDisplayLength": 5,
            "aLengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
            "oTableTools": {
                "sSwfPath": "vendor/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
            }
        });

        // Init Select2 - Basic Single
        $(".select2-single").select2();

        // Init Select2 - Basic Multiple
        $(".select2-multiple").select2({
          placeholder: "Select a state",
           allowClear: true
        });

        // Init DateTimepicker - fields
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            pickTime: false
        });

        $('.date').datetimepicker({
            format: 'YYYY',
            viewMode: 'years',
            minViewMode: "years",
            pickTime: false
        });

        $('.mydatepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            pickTime: false
        });

        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker();
        $('#datetimepicker3').datetimepicker({
          defaultDate: "9/4/2014",
          inline: true,
        });

    });
</script>

@yield('script')

<?php $messages= ['success','danger','warning']; foreach($messages as $msg){?>
@if(Session($msg))
    <script type="text/javascript">
        // Create new Notification
        new PNotify({
            title: '{{ucfirst($msg)}} Message',
            text: '{{Session($msg)}}',
            shadow: true,
            addclass: 'stack_top_right',
            type: '{{$msg}}',
            width: '290px',
            delay: 2000
        });
    </script>
@endif
<?php }?>


<!-- END: PAGE SCRIPTS -->

</body>

</html>
