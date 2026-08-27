@extends('emails.layouts.master')

@section('title', 'Thank you for applying')
@section('heading', 'Thank You for Applying')
@section('subtitle', 'We have received your application')

@section('content')

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    Dear {{ $application->first_name }},
  </p>

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    Thank you for your interest in <strong>Catalyst Trusteeship Limited</strong>. We have received your
    application along with your resume, and our HR team will review it shortly. If your profile matches
    our requirements, we will be in touch at this email address.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Your Application</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px; background-color:#fff3f0;">
    <tr>
      <td style="padding:11px 16px; width:180px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Position</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;"><strong>{{ $application->position }}</strong></td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Name</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $application->full_name }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Phone</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $application->phone }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">City</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $application->city }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69;">Resume</td>
      <td style="padding:11px 16px;">{{ $application->resume_original_name ?: 'Received' }}</td>
    </tr>
  </table>

  <p style="margin:0 0 6px; font-size:14px; line-height:1.7;">
    Warm regards,
  </p>
  <p style="margin:0; font-size:14px; line-height:1.7;">
    <strong style="color:#c9624c;">Catalyst Trusteeship Limited</strong>
  </p>

@endsection

@section('note', 'This is an automated acknowledgement — please do not reply to this email.')
