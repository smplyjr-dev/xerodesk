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
                Hi {{ $session->client->name }},
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
              <p style="margin: 0;">One of your ticket has been updated.</p>
            </td>
          </tr>

          <tr>
            <td align="left" bgcolor="#ffffff" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
              <p style="margin-top: 0;">Please find the details down below.</p>
              <table border="0" cellpadding="0" cellspacing="0" width="50%" style="max-width: 600px;">
                <tr>
                  <td>Ticket #:</td>
                  <td>{{ $session->session }}</td>
                </tr>

                @if (!is_null($session->user_id))
                  <tr>
                    <td>Agent:</td>
                    <td>{{ $session->user->bio->first_name }} {{ $session->user->bio->last_name }}</td>
                  </tr>
                @endif

                @if (!is_null($session->title))
                  <tr>
                    <td>Title:</td>
                    <td>{{ $session->title }}</td>
                  </tr>
                @endif

                @if (!is_null($session->priority))
                  @php $priority = collect(constants('ticket.priority'))->where('id', $session->priority)->first(); @endphp
                  <tr>
                    <td>Priority:</td>
                    <td>{{ $priority['name'] }}</td>
                  </tr>
                @endif

                @if (!is_null($session->category))
                  <tr>
                    <td>Category:</td>
                    <td>{{ $session->category }}</td>
                  </tr>
                @endif

                @if (!is_null($session->resolution_code))
                  <tr>
                    <td>Resolution Code:</td>
                    <td>{{ $session->resolution_code }}</td>
                  </tr>
                @endif

                @if (!is_null($session->solution))
                  <tr>
                    <td>Solution:</td>
                    <td>{{ $session->solution }}</td>
                  </tr>
                @endif

                @if (!is_null($session->status))
                  @php $status = collect(constants('ticket.status'))->where('id', $session->status)->first(); @endphp
                  <tr>
                    <td>Status:</td>
                    <td>{{ $status['name'] }}</td>
                  </tr>
                @endif

                <tr>
                  <td>Updated Date:</td>
                  <td>{{ $session->updated_at->format('m/d/Y h:i A') }}</td>
                </tr>
              </table>
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
