<!DOCTYPE html>
<html>

<head>

  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>FiliChat | Chat Log</title>

  <style type="text/css">
    /**
    * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
    */
    @media screen {
      @font-face {
        font-family: 'Source Sans Pro';
        font-style: normal;
        font-weight: 400;
        src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
      }

      @font-face {
        font-family: 'Source Sans Pro';
        font-style: normal;
        font-weight: 700;
        src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
      }
    }

    /**
    * Avoid browser level font resizing.
    * 1. Windows Mobile
    * 2. iOS / OSX
    */
    body,
    table,
    td,
    a {
      -ms-text-size-adjust: 100%;
      /* 1 */
      -webkit-text-size-adjust: 100%;
      /* 2 */
    }

    /**
    * Remove extra space added to tables and cells in Outlook.
    */
    table,
    td {
      mso-table-rspace: 0pt;
      mso-table-lspace: 0pt;
    }

    /**
    * Better fluid images in Internet Explorer.
    */
    img {
      -ms-interpolation-mode: bicubic;
    }

    /**
    * Remove blue links for iOS devices.
    */
    a[x-apple-data-detectors] {
      font-family: inherit !important;
      font-size: inherit !important;
      font-weight: inherit !important;
      line-height: inherit !important;
      color: inherit !important;
      text-decoration: none !important;
    }

    /**
    * Fix centering issues in Android 4.4.
    */
    div[style*="margin: 16px 0;"] {
      margin: 0 !important;
    }

    body {
      width: 100% !important;
      height: 100% !important;
      padding: 0 !important;
      margin: 0 !important;
    }

    /**
    * Collapse table borders to avoid space between cells.
    */
    table {
      border-collapse: collapse !important;
    }

    a {
      color: #1a82e2;
    }

    img {
      height: auto;
      line-height: 100%;
      text-decoration: none;
      border: 0;
      outline: none;
    }
  </style>

</head>

<body style="background-color: #e9ecef;">

  <!-- start preheader -->
  <div class="preheader"
    style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    A preheader is the short summary text that follows the subject line when an email is viewed in the inbox.
  </div>

  <!-- start body -->
  <table border="0" cellpadding="0" cellspacing="0" width="100%">

    <!-- start logo -->
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="center" valign="top" style="padding: 36px 24px;">
              <a href="#" target="_blank" style="display: inline-block;">
                <img src="{{ url('images/logo.png') }}" alt="Logo" border="0" width="48" style="display: block; width: 150px; max-width: 150px; min-width: 48px;">
              </a>
            </td>
          </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
      </td>
    </tr>

    <!-- start hero -->
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
              <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; color: #4d4d4d;">Hi {{ $client->name }},</h1>
            </td>
          </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
      </td>
    </tr>

    <!-- start copy block -->
    <tr>
      <td align="center" bgcolor="#e9ecef">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
        <tr>
        <td align="center" valign="top" width="600">
        <![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
          <!-- Heading: Sentences -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">Please find the below chat log of your session.</p>
            </td>
          </tr>

          <!-- Body: Chat Log -->
          <tr>
            <td align="left" bgcolor="#ffffff">
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin:0 15px;" width="100%">
                      <tr>
                        <td align="center" style=" padding:20px 20px 40px 20px;">
                          @foreach ($messages as $message)
                            @if ($message->message_from == 'admin')
                              <table style="margin-top: 15px;">
                                <tbody>
                                  <tr>
                                    <td>
                                      @if ($user->profile_picture !== 'generic-profile.png')
                                        <img style="border-radius: 30px;" src="{{ url('/storage/uploads/users/' . $user->id . '/' . $user->profile_picture) }}" width="30px" />
                                      @else
                                        <img style="border-radius: 30px;" src="{{ url('/images/generic-profile.png') }}" width="30px" />
                                      @endif
                                    </td>
                                    <td align="left" bgcolor="#ffffff" style="font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-left: 10px;">
                                      {{ $user->bio->first_name . ' ' . $user->bio->last_name }}
                                      <span style="text-align: center; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 11px; font-style: italic; line-height: 30px;">{{ $message->created_at }}</span>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td align="left" bgcolor="#ffffff" style="padding: 10px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 24px; background-color:#f2f2f2; border-radius: 6px;">
                                      <p style="margin: 0;">{!! $message->message !!}</p>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table>
                            @endif

                            @if ($message->message_from == 'client')
                              <table style="margin-top: 15px;">
                                <tbody>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align="right" bgcolor="#ffffff" style="font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; padding-right: 10px;">
                                      {{ $client->name }}
                                      <span style="text-align: center; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 11px; font-style: italic; line-height: 30px;">{{ $message->created_at }}</span>
                                    </td>
                                    <td>
                                      @if (is_null($client->picture))
                                        <img style="border-radius: 30px;" src="{{ url('/images/generic-profile.png') }}" width="30px" />
                                      @else
                                        <img style="border-radius: 30px;" src="{{ url($client->company->url . '/' . $client->picture) }}" width="30px" />
                                      @endif
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align="left" bgcolor="#ffffff" style="padding: 10px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 24px; background-color: rgb(215, 250, 234); border-radius: 6px;">
                                      <p style="margin: 0;">{!! $message->message !!}</p>
                                    </td>
                                    <td>&nbsp;</td>
                                  </tr>
                                </tbody>
                              </table>
                            @endif
                          @endforeach
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 13px; line-height: 24px; border-bottom: 3px solid #d4dadf; text-align: center;">
              <p style="margin: 0;">
                Have Questions: We are here to help. Learn more <a href="#">here</a> or <a href="#">contact us</a>
              </p>
            </td>
          </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
      </td>
    </tr>
  </table>

</body>

</html>