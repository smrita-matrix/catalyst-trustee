@extends('emails.layouts.master')

@section('title', 'New Website Enquiry')
@section('heading', 'New Website Enquiry')
@section('subtitle')
  Received {{ $enquiry->created_at ? \Carbon\Carbon::parse($enquiry->created_at)->format('d M Y, H:i') : '' }}
@endsection

@section('content')

  <p style="margin:0 0 18px; font-size:14px; line-height:1.7;">
    Someone has submitted the enquiry form on the website.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Service Enquired About</p>
  <p style="margin:0 0 22px; padding:12px 16px; background-color:#fff3f0; border-left:3px solid #c9624c; font-size:14px;">
    <strong>{{ $enquiry->service }}</strong>
    @if($enquiry->location)
      <br><span style="color:#6f6b69;">Location: {{ $enquiry->location }}</span>
    @endif
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Contact Details</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px;">
    <tr>
      <td style="padding:9px 0; width:180px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Name</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;"><strong>{{ $enquiry->full_name }}</strong></td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">Email</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">
        <a href="mailto:{{ $enquiry->email }}" style="color:#c9624c; text-decoration:none;">{{ $enquiry->email }}</a>
      </td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69;">Mobile</td>
      <td style="padding:9px 0;">
        <a href="tel:{{ preg_replace('/\s+/', '', $enquiry->mobile) }}" style="color:#444343; text-decoration:none;">{{ $enquiry->mobile }}</a>
      </td>
    </tr>
  </table>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Comments / Questions</p>
  <div style="margin:0 0 22px; padding:14px 16px; background-color:#fff3f0; border-left:3px solid #c9624c; font-size:14px; line-height:1.7;">
    {!! nl2br(e($enquiry->comments)) !!}
  </div>

  <p style="margin:0; padding-top:16px; border-top:1px solid #efe7e3; font-size:13px; color:#6f6b69;">
    Reply to this email to respond directly to the enquirer.
  </p>

@endsection

@section('note', 'Sent automatically from the Contact Us page on the website.')
