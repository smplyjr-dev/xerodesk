@extends('emails.session.layout')

@section('content')

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
              <a href="#" target="_blank" style="display: inline-block; color: #4d4d4d; text-decoration: none; font-family: 'Source Sans Pro'">
                <h1>Xerodesk</h1>
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
              <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; color: #4d4d4d;">
                Hi Admin,
              </h1>
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
          <!-- start copy -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">A new ticket has been created in Xerodesk Portal.</p>
            </td>
          </tr>

          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin-top: 0;">Please find the details down below.</p>

              <table border="0" cellpadding="0" cellspacing="0" width="50%" style="max-width: 600px;">
                <tr>
                  <td>Ticket #:</td>
                  <td><a href="{{ url('/') }}/tickets/{{ $session->session }}">{{ $session->session }}</td></a>
                </tr>
                <tr>
                  <td>Created By:</td>
                  <td>{{ $session->client->name }}</td>
                </tr>
                <tr>
                  <td>Created Date:</td>
                  <td>{{ $session->created_at->format('m/d/Y h:i A') }}</td>
                </tr>
              </table>

              <p style="margin-bottom: 0;">You may click the ticket # to open the ticket directly.</p>
            </td>
          </tr>

          <!-- start copy -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin: 0;">Thank you for your support!</p>
            </td>
          </tr>

          <!-- start copy -->
          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 13px; line-height: 24px; border-bottom: 3px solid #d4dadf; text-align: center;">
              <p style="margin: 0;">Have Questions: We are here to help. Learn more <a href="#">here</a> or <a href="#">contact us</a></p>
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

@endsection
