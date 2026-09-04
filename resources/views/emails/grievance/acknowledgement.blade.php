@extends('emails.layouts.master')

@section('title', 'We have received your grievance')
@section('heading', 'Thank You for Reaching Out')
@section('subtitle', $grievance->type_label)

@section('content')

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    Dear {{ $grievance->full_name }},
  </p>

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    We have received your grievance and our team has begun reviewing it. We will get back to you
    at this email address as soon as possible.
  </p>

  <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
    It was submitted under <strong>{{ $grievance->type_label }}</strong>.
  </p>

  <p style="margin:0 0 10px; font-size:15px; font-weight:bold; color:#c9624c;">A Summary of What You Submitted</p>

  {{-- Only the fields this form actually asked for. --}}
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; border-collapse:collapse; margin-bottom:22px; background-color:#fff3f0;">
    @php $rows = $grievance->summaryRows(); $last = array_key_last($rows); @endphp
    @foreach($rows as $label => $value)
    <tr>
      <td style="padding:11px 16px; width:190px; color:#6f6b69; vertical-align:top; {{ $label === $last ? '' : 'border-bottom:1px solid #efe7e3;' }}">{{ $label }}</td>
      <td style="padding:11px 16px; vertical-align:top; {{ $label === $last ? '' : 'border-bottom:1px solid #efe7e3;' }}">{!! nl2br(e($value)) !!}</td>
    </tr>
    @endforeach
  </table>

  @if($grievance->type === 'sebi')
    <p style="margin:0 0 16px; font-size:14px; line-height:1.7;">
      You may also register complaints on the SEBI SCORES portal at
      <a href="https://www.scores.gov.in/" style="color:#c9624c; text-decoration:none;">https://www.scores.gov.in/</a>.
    </p>
  @else
    <p style="margin:0 0 16px; font-size:13px; line-height:1.7; color:#6f6b69;">
      Please note that the SEBI investor protection mechanisms are not available for grievances
      relating to activities that are not regulated by SEBI.
    </p>
  @endif

  <p style="margin:0 0 6px; font-size:14px; line-height:1.7;">Warm regards,</p>
  <p style="margin:0; font-size:14px; line-height:1.7;">
    <strong style="color:#c9624c;">Catalyst Trusteeship Limited</strong>
  </p>

@endsection

@section('note', 'This is an automated acknowledgement — please do not treat it as a resolution of your grievance.')
