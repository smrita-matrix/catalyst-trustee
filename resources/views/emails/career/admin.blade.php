@extends('emails.layouts.master')

@section('title', 'New Job Application')
@section('heading', 'New Job Application')
@section('subtitle')
  Received {{ $application->created_at ? \Carbon\Carbon::parse($application->created_at)->format('d M Y, H:i') : '' }}
@endsection

@section('content')

  <p style="margin:0 0 18px; font-size:14px; line-height:1.7;">
    A candidate has applied through the website. Their resume is attached to this email.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Position Applied For</p>
  <p style="margin:0 0 22px; padding:12px 16px; background-color:#fff3f0; border-left:3px solid #c9624c; font-size:14px;">
    <strong>{{ $application->position }}</strong>
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Candidate Details</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px;">
    <tr>
      <td style="padding:9px 0; width:180px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Name</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;"><strong>{{ $application->full_name }}</strong></td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">Email</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">
        <a href="mailto:{{ $application->email }}" style="color:#c9624c; text-decoration:none;">{{ $application->email }}</a>
      </td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">Phone</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">
        <a href="tel:{{ preg_replace('/\s+/', '', $application->phone) }}" style="color:#444343; text-decoration:none;">{{ $application->phone }}</a>
      </td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69;">City</td>
      <td style="padding:9px 0;">{{ $application->city }}</td>
    </tr>
  </table>

  @if($application->intro)
    <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Intro &amp; Why We Should Hire Them</p>
    <div style="margin:0 0 22px; padding:14px 16px; background-color:#fff3f0; border-left:3px solid #c9624c; font-size:14px; line-height:1.7;">
      {!! nl2br(e($application->intro)) !!}
    </div>
  @endif

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Resume</p>
  <p style="margin:0 0 22px; font-size:14px;">
    <span style="display:inline-block; padding:10px 16px; background-color:#fff3f0; border:1px solid #e8ded9; border-radius:6px;">
      {{ $application->resume_original_name ?: 'Attached to this email' }}
    </span>
  </p>

  <p style="margin:0; padding-top:16px; border-top:1px solid #efe7e3; font-size:13px; color:#6f6b69;">
    Reply to this email to contact the candidate directly.
  </p>

@endsection

@section('note', 'Sent automatically from the Careers page on the website.')
