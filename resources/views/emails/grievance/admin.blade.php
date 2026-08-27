@extends('emails.layouts.master')

@section('title', 'New Investor Grievance')
@section('heading', 'New Investor Grievance')
@section('subtitle')
  Received {{ $grievance->created_at ? \Carbon\Carbon::parse($grievance->created_at)->format('d M Y, H:i') : '' }}
@endsection

@section('content')

  <p style="margin:0 0 18px; font-size:14px; line-height:1.7;">
    An investor has submitted a grievance through the website.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Investor / Debenture Holder</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px;">
    <tr>
      <td style="padding:9px 0; width:190px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Full Name</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;"><strong>{{ $grievance->full_name }}</strong></td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">PAN</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">{{ $grievance->pan }}</td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">Email</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">
        <a href="mailto:{{ $grievance->email }}" style="color:#c9624c; text-decoration:none;">{{ $grievance->email }}</a>
      </td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">Mobile</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">{{ $grievance->mobile ?: '-' }}</td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; vertical-align:top;">Postal Address</td>
      <td style="padding:9px 0;">{!! nl2br(e($grievance->address)) !!}</td>
    </tr>
  </table>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Instrument Details</p>
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px;">
    <tr>
      <td style="padding:9px 0; width:190px; color:#6f6b69; border-bottom:1px solid #efe7e3;">Debenture Issuer</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">{{ $grievance->issuer_name }}</td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">Series Name</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">{{ $grievance->series_name ?: '-' }}</td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">ISIN</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">{{ $grievance->isin }}</td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; border-bottom:1px solid #efe7e3;">No of Bonds Held</td>
      <td style="padding:9px 0; border-bottom:1px solid #efe7e3;">{{ $grievance->bonds_held }}</td>
    </tr>
    <tr>
      <td style="padding:9px 0; color:#6f6b69; vertical-align:top;">Complaint Particulars</td>
      <td style="padding:9px 0;">{{ implode(', ', (array) $grievance->complaint_types) }}</td>
    </tr>
  </table>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">Details of Grievance</p>
  <div style="margin:0 0 22px; padding:14px 16px; background-color:#fff3f0; border-left:3px solid #c9624c; font-size:14px; line-height:1.7;">
    {!! nl2br(e($grievance->complaint_details)) !!}
  </div>

  <p style="margin:0; padding-top:16px; border-top:1px solid #efe7e3; font-size:13px; color:#6f6b69;">
    Reply to this email to contact the investor directly.
  </p>

@endsection

@section('note', 'Sent automatically from the Investor Grievance form on the website.')
