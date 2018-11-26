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
            <h2>Reset Password(Setup)</h2>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <div style="clear:both"></div>
            <form id="login" class="form-horizontal" role="form" method="POST" action="{{ url('setup/password/reset') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="Email Address" required autofocus>
                    @if ($errors->has('email'))
                        <span  class="error-login">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>

                <div>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span  class="error-login">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                </div>

                <div>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                    @if ($errors->has('password_confirmation'))
                        <span  class="error-login">
                            {{ $errors->first('password_confirmation') }}
                        </span>
                    @endif
                </div>

                <button class="btn btn-primary btn-login" id="voyager-login-btn">
                    <span class="login_text"><i class="voyager-lock"></i> Reset Password</span>
                </button>

            </form>

            @if ($errors->has('unauthenticated'))
                <span  class="error-login">
                    {{ $errors->first('unauthenticated') }}
                </span>
            @endif

        </div>
    </div>

@endsection
