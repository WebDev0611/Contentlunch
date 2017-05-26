<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Content Launch: {{$title}}</title>
    <style type="text/css">
        h3{margin: 0 auto 20px;font-size:16px;}
        p{font-size:14px;line-height: 24px;}
        button {text-align: center; padding: 4px 30px; margin-top:10px; background-color: #2482ff; color:#fff; border: none;}
        a button {cursor:pointer;}
        table{text-align: left;font-size:14px;}
        table td{vertical-align: top; padding: 5px;}
    </style>
</head>

<body style="padding: 30px 0; min-width: 100%!important;-ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%;font-family: Tahoma, Geneva, sans-serif;text-align:center;background-color:#F2F2F2;color:#474747;">

<div class="content" style="width: 100%; max-width: 600px;margin: 0 auto; padding: 20px; background-color:#fff;">

    <div class="logo" style="margin: 10px 0 30px;">
        <img src="<?php echo $message->embed(public_path() . '/images/logo-full.png'); ?>" width="215" border="0" alt="Content Launch Logo" title="Content Launch Logo">
    </div>
    <h3>{{$title}}</h3>

    @yield('content')

    <p class="thanks" style="text-align: left; padding-left: 20px;margin-top: 40px;">
        Best regards,<br>
        Content Launch Team
    </p>
</div>

</body>
</html>