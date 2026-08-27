@extends('emails.layouts.master')

@section('title', 'We have received your grievance')
@section('heading', 'Thank You for Reaching Out')
@section('subtitle', 'We have received your grievance')

@section('content')

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    Dear {{ $grievance->full_name }},
  </p>

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    We have received your grievance and our team has begun reviewing it. We will get back to you
    at this email address as soon as possible.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">A Summary of What You Submitted</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px; background-color:#fff3f0;">
    <tr>
      <td style="padding:11px 16px; width:190px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Debenture Issuer</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;"><strong>{{ $grievance->issuer_name }}</strong></td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">ISIN</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $grievance->isin }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">No of Bonds Held</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $grievance->bonds_held }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3; vertical-align:top;">Complaint Particulars</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ implode(', ', (array) $grievance->complaint_types) }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; vertical-align:top;">Your Message</td>
      <td style="padding:11px 16px;">{!! nl2br(e($grievance->complaint_details)) !!}</td>
    </tr>
  </table>

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    You may also register complaints on the SEBI SCORES portal at
    <a href="https://www.scores.gov.in/" style="color:#c9624c; text-decoration:none;">https://www.scores.gov.in/</a>.
  </p>

  <p style="margin:0 0 6px; font-size:14px; line-height:1.7;">Warm regards,</p>
  <p style="margin:0; font-size:14px; line-height:1.7;">
    <strong style="color:#c9624c;">Catalyst Trusteeship Limited</strong>
  </p>

@endsection

@section('note', 'This is an automated acknowledgement — please do not treat it as a resolution of your grievance.')
