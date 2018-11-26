<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="hrms login">
    <meta name="author" content="IDDL">
    <title>HRMS Login</title>
    <link rel="shortcut icon" href="{{asset('img/logo.png')}}">
    <link rel="stylesheet" href="{{asset('css/auth.css')}}">
</head>
<body class="login-page">
<div id="bgdim"></div>

@yield('content')

<script>
    login_btn = document.getElementById("voyager-login-btn");
    login_btn.addEventListener("click", function () {
        var originalHeight = login_btn.offsetHeight;
        login_btn.style.height = originalHeight + 'px';
        document.querySelector('#voyager-login-btn span.login_text').style.display = 'none';
        document.querySelector('#voyager-login-btn span.login_loader').style.display = 'block';
        document.querySelector('.btn-loading').style.display = 'block';
    });
</script>

</body>
</html>
