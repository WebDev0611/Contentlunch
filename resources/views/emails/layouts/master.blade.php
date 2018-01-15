<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <title>Content Launch</title>

    <style type="text/css">      
      @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700');
      p {
        margin: 1em 0;
        line-height:normal;
        font-size: 16px;
      }
      @media screen and (max-width: 630px){
        *[class="100p"] {
            width:100% !important; 
            height:auto !important;
        }
      }
    </style>
  </head>
  <body style="width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; background:#F2F3F9; font-family: 'Source Sans Pro', Arial, Helvetica, sans-serif; color:#392C46;">
    <!-- Background Table --> 
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#fff" style="background:#F2F3F9; padding:12px 10px 0 10px;">
      <tr>
        <td align="center" valign="top" class="100p">
          <!-- Content Table -->
          <table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="100p" style="padding: 0">
            <!-- Content Block: Header -->
            <tr>
              <td valign="top" align="center" style="padding: 35px 0; background-color: #ffffff;" class="100p">
                <a href="#" style="text-decoration: none; outline: none;">
                  <img style="width: 190px; height: auto; vertical-align: middle;" src="<?php echo $message->embed(public_path() . '/images/emails/logo-contentlaunch.png'); ?>"  alt="Content Launch Logo">
                  <p style="font-size: 12px; color:#999999;">Content Marketing Software Simplified</p>
                </a>
              </td>
            </tr>
            <!-- Content Block: New Speakers -->
            <tr>
              <td width="650" class="100p" valign="top" align="center" style="text-align: center; background-color: #ffffff;">
                <table width="460" border="0" cellpadding="0" cellspacing="0" class="100p" align="center">                  
                  <tbody>
                    <tr>
                      <td style="text-align: left; padding: 35px 25px; padding-top: 0;">
                      @if( isset($image) )
                        <p style="text-align: center">
                          <img style="width: 140px; height: auto; vertical-align: middle;" src="<?php echo $message->embed(public_path() . '/images/emails/' . $image); ?>" />
                        </p>
                      @endif
                      @if(isset($title)) <h1 style="font-weight: bold; font-size: 30px; color:#392C46; line-height: 1.2em; margin: 15px 0; margin-bottom: 40px; text-align: center;">{{ $title }}</h1> @endif
                      @yield('content')
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <!-- Content Block: Footer -->
            <tr>
              <td style="padding-bottom: 60px;">
                <p style="font-size: 13px; color: rgba(57, 44, 70, .5); text-align: center; margin: 0; margin-top: 30px; line-height: 1.1em; text-decoration: none;">&copy; Copyright 2018 ContentLaunch</p>
              </td>
            </tr>
          </table> <!-- End Content Table -->
        </td>
      </tr>
    </table> <!-- End Background Table -->
  </body>
</html>