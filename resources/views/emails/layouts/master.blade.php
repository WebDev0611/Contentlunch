<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Content Launch: {{$title}}</title>
    <style type="text/css">
        body {padding: 30px 0; min-width: 100%!important;-webkit-font-smoothing: antialiased;font-family: Tahoma, Geneva, sans-serif;text-align:center;background-color:#F2F2F2;color:#474747;}
        .content {width: 100%; max-width: 600px;margin: 0 auto; padding: 20px; background-color:#fff;}
        .logo{margin: 10px 0 30px;}
        h3{margin: 0 auto 20px;font-size:16px;}
        p{font-size:14px;line-height: 24px;}
        button {text-align: center; padding: 3px 30px; margin-top:10px; background-color: #2482ff; color:#fff; border: none;}
        a button {cursor:pointer;}
        .thanks{text-align: left; padding-left: 20px;}
    </style>
</head>
<body>

<div class="content">
    <div class="logo">
        <img src="{{asset('images/logo-full.svg')}}" width="215" border="0" alt="" />
    </div>
    <h3>{{$title}}</h3>

    @yield('content')

    <p class="thanks">
        Thanks,<br>
        Content Launch Team
    </p>
</div>

</body>
</html>