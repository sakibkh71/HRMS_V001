@extends('layouts.hrms')
@section('content')

@section('style')
    <link rel="stylesheet" href="{{asset('orgChart/dist/css/jquery.orgchart.css')}}">
    <link rel="stylesheet" href="{{asset('orgChart/dist/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('orgChart/style.css')}}">
@endsection

<!-- Begin: Content -->
<section id="content" class="animated fadeIn">

<!-- Accessed Companyes -->
@if(count($getAccessCompanies)>0)
  <div class="row">
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><b>Companies</b></span>
        </div>
        <div class="panel-body panel-scroller scroller-dark scroller-overlay scroller-pn pn" style="height: 350px;">
          <table class="table">
            <thead>
              <tr>
                <th>Company Code</th>
                <th>Company Name</th>
                <!-- <th>Company Address</th>
                <th>Created Date</th> -->
                 <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($getAccessCompanies as $minfo)
              <tr>
                <td>{{$minfo->company_code}}</td>
                <td>{{$minfo->company_name}}</td>
                {{--<td>{{$minfo->company_address}}</td>
                <td>{{$minfo->created_at}}</td>--}}
                <td>
                  <form action="{{url('/switch/account/'.$minfo->database_name.'/'.$minfo->id)}}" method="post">
                  {{csrf_field()}}
                  <input type="submit" name="submit" value="Switch Account" class="btn btn-sm btn-info btn-gradient">
                </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4"><h2>Data not available</h2></td>
              </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><b>Notification</b></span>
        </div>
        <!-- scroller-sm -->
        <div class="panel-body panel-scroller scroller-dark scroller-overlay scroller-pn pn" style="height: 350px;">
          @if(count($emp_type_will_changed) > 0)
          <table class="table">
            <tr>
              <th class="text-danger">Type Will Changed With In 30 Days</th>
            </tr>
            @foreach($emp_type_will_changed as $info)
              <tr>
                <td>{{ $info['name']."(".$info['no'].")" }}</td>
              <tr>
            @endforeach
          </table>
          @endif

          @if(count($emp_status_will_changed) > 0)
          <table class="table">
            <tr>
              <th class="text-danger">Status Will Changed With In 30 Days</th>
            </tr>
            @foreach($emp_status_will_changed as $info)
              <tr>
                <td>{{ $info['name']."(".$info['no'].")" }}</td>
              <tr>
            @endforeach
          </table>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel">
        <div class="panel-heading">
          <span class="panel-title"><b>Employee Counter</b></span>
        </div>
        <!-- scroller-sm -->
        <div class="panel-body panel-scroller scroller-dark scroller-overlay scroller-pn pn" style="height: 350px;">
          @if(count($depWithUserAry) > 0)
          <table class="table">
            <tr>
              <th>Department Name</th>
              <th>User Amount</th>
            </tr>
            <?php $counter = 0; ?>
            @foreach($depWithUserAry as $info)
              <tr>
                <td>{{$info['dep_name']}}</td>
                <td>{{$info['user']}}</td>
                <?php $counter = $counter + $info['user']; ?>
              </tr>
            @endforeach
            <tr>
              <th>Total Active Employee:</th>
              <th>{{ $counter }}</th>
            </tr>
          </table>
          @endif
        </div>
      </div>
    </div>
  </div>
@endif

<!-- organogram -->
  <div>
    <div class="panel">
      <div class="panel-heading">
        <span class="panel-title">Organogram</span>
      </div>
      <div class="panel-body">
        <div id="chart-container"></div>
      </div>
    </div>
  </div>


</section>
<!-- End: Content -->



  <script type="text/javascript">

    var data = '<?php echo json_encode($organogram);?>';
    var datascource = JSON.parse(data);
    var config_id = {{Session('config_id')}}
    // console.log(datascource);

  </script>
  <script type="text/javascript" src="{{asset('orgChart/dist/js/jquery-3.1.0.min.js')}}"></script>

  <script type="text/javascript" src="https://cdn.rawgit.com/stefanpenner/es6-promise/master/dist/es6-promise.auto.min.js"></script>
  <script type="text/javascript" src="https://cdn.rawgit.com/niklasvh/html2canvas/master/dist/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

  <script type="text/javascript" src="{{asset('orgChart/dist/js/jquery.orgchart.js')}}"></script>
  <script type="text/javascript" src="{{asset('orgChart/scripts.js')}}"></script>



@endsection