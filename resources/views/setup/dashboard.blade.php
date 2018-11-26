@extends('layouts.setup')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">User List</div>

                <div class="panel-body">
                    {{-- {{Auth::user()->user_type}} --}}
                    <table class="table table-hover">
                        <thead>
                            <tr class="success">
                                <th>sl</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Mobile Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $sl = 1;
                            ?>
                            @foreach($users as $user)
                                @if($user->user_type != 1)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>
                                            <a href="{{url("setup/details/$user->id")}}">{{$user->full_name}}</a>
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @if($user->user_type==1)
                                                {{"Admin"}}
                                            @else
                                                {{"User"}}
                                            @endif
                                        </td>
                                        <td>{{$user->mobile_number}}</td>
                                        <td class="
                                            @if($user->status==0)
                                                {{"danger"}}
                                            @endif
                                        ">
                                            @if($user->status == 1)
                                                {{"Active"}}
                                            @else
                                                {{"Deactive"}}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection