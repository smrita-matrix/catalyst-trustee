@extends('emails.layouts.master')

@section('title', 'New Investor Grievance')
@section('heading', 'New Investor Grievance')
@section('subtitle')
  {{ $grievance->type_label }}
@endsection

@section('content')

  <p style="margin:0 0 18px; font-size:14px; line-height:1.7;">
    A grievance has been submitted through the website under
    <strong>{{ $grievance->type_label }}</strong>.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">The Grievance</p>

  {{-- Only the fields this form asked for, plus the sender's contact details
       so the team can reply without opening the dashboard. --}}
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px;">
    @php $rows = $grievance->summaryRows(true); $last = array_key_last($rows); @endphp
    @foreach($rows as $label => $value)
    <tr>
      <td style="padding:9px 0; width:190px; color:#6f6b69; vertical-align:top; {{ $label === $last ? '' : 'border-bottom:1px solid #efe7e3;' }}">{{ $label }}</td>
      <td style="padding:9px 0; vertical-align:top; {{ $label === $last ? '' : 'border-bottom:1px solid #efe7e3;' }}">
        @if($label === 'Email')
          <a href="mailto:{{ $value }}" style="color:#c9624c; text-decoration:none;">{{ $value }}</a>
        @elseif($label === 'Mobile')
          <a href="tel:{{ preg_replace('/\s+/', '', $value) }}" style="color:#c9624c; text-decoration:none;">{{ $value }}</a>
        @elseif($label === 'Full Name')
          <strong>{{ $value }}</strong>
        @else
          {!! nl2br(e($value)) !!}
        @endif
      </td>
    </tr>
    @endforeach
  </table>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px; border-collapse:collapse; margin-bottom:22px; background-color:#f7f6f4;">
    <tr>
      <td style="padding:10px 14px; width:190px; color:#6f6b69;">Received</td>
      <td style="padding:10px 14px;">{{ $grievance->created_at ? \Carbon\Carbon::parse($grievance->created_at)->format('d M Y, H:i') : '' }}</td>
    </tr>
    @if($grievance->ip_address)
    <tr>
      <td style="padding:10px 14px; color:#6f6b69;">Submitted from</td>
      <td style="padding:10px 14px;">{{ $grievance->ip_address }}</td>
    </tr>
    @endif
  </table>

  <p style="margin:0; font-size:13px; line-height:1.7; color:#6f6b69;">
    The person who submitted this has been sent an acknowledgement at
    <a href="mailto:{{ $grievance->email }}" style="color:#c9624c; text-decoration:none;">{{ $grievance->email }}</a>.
  </p>

@endsection

@section('note', 'Sent automatically from the website. Reply to the investor at the address above.')
