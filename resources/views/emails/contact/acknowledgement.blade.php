@extends('emails.layouts.master')

@section('title', 'Thank you for contacting us')
@section('heading', 'Thank You for Contacting Us')
@section('subtitle', 'We have received your enquiry')

@section('content')

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    Dear {{ $enquiry->first_name }},
  </p>

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    Thank you for getting in touch with <strong>Catalyst Trusteeship Limited</strong>. We have received
    your enquiry and a member of our team will respond at this email address shortly.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Your Enquiry</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px; background-color:#fff3f0;">
    <tr>
      <td style="padding:11px 16px; width:180px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Service</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;"><strong>{{ $enquiry->service }}</strong></td>
    </tr>
    @if($enquiry->location)
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Location</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $enquiry->location }}</td>
    </tr>
    @endif
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Name</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $enquiry->full_name }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Mobile</td>
      <td style="padding:11px 16px; border-bottom:1px solid #efe7e3;">{{ $enquiry->mobile }}</td>
    </tr>
    <tr>
      <td style="padding:11px 16px; color:#6f6b69; vertical-align:top;">Your Message</td>
      <td style="padding:11px 16px;">{!! nl2br(e($enquiry->comments)) !!}</td>
    </tr>
  </table>

  <p style="margin:0 0 6px; font-size:14px; line-height:1.7;">Warm regards,</p>
  <p style="margin:0; font-size:14px; line-height:1.7;">
    <strong style="color:#c9624c;">Catalyst Trusteeship Limited</strong>
  </p>

@endsection

@section('note', 'This is an automated acknowledgement — please do not reply to this email.')
