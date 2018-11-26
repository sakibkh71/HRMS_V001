@extends('layouts.auth')

@section('content')


    <div id="title_section">
        <img class="logo-img" src="{{asset('img/logo.png')}}" alt="Logo Icon">
        <div class="copy">
            <h1>[ LAMBA</h1>
            <p>Welcome to LAMBA. The Human Resource Management System from IDDL.</p>
        </div>
        <div style="clear:both"></div>
    </div>

    <div id="login_section">
        <div class="content">
            <h2>Reset Password</h2>
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div style="clear:both"></div>
            <form id="login" class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}

                <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" required>
                    @if ($errors->has('email'))
                        <span  class="error-login">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>

                <button class="btn btn-primary btn-login" id="voyager-login-btn">
                    <span class="login_text"><i class="voyager-lock"></i> Send Password Reset Link</span>
                </button>
            </form>
        </div>
    </div>

@endsection
