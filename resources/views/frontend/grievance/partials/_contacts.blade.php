{{-- Who to write to, and anything to open alongside the page.

     Beside a form this is a narrow column, so each line sits on its own. On a
     page with no form it runs down the middle of the page as a list, which is
     how the client's own page reads. --}}
@php $asList = $asList ?? false; @endphp

@if($contacts->count())
@if($asList)
<ul class="grievance-contact-list">
  @foreach($contacts as $c)
  @php $kind = $c['kind'] ?? 'text'; $value = trim($c['value'] ?? ''); @endphp
  <li>
    @if(trim($c['label'] ?? '') !== '')<strong>{{ $c['label'] }}</strong>@if($value !== ''): @endif @endif
    @if($kind === 'email')
      <a href="mailto:{{ $value }}">{{ $value }}</a>
    @elseif($kind === 'phone')
      <a href="tel:{{ preg_replace('/\s+/', '', $value) }}">{{ $value }}</a>
    @else
      {{ $value }}
    @endif
  </li>
  @endforeach
</ul>
@else
<div class="grievance-officer">
  @foreach($contacts as $c)
  @php $kind = $c['kind'] ?? 'text'; $value = trim($c['value'] ?? ''); @endphp
  <p>
    @if(trim($c['label'] ?? '') !== '')<strong>{{ $c['label'] }}</strong>@if($value !== '') &ndash; @endif @endif
    @if($kind === 'email')
      <a href="mailto:{{ $value }}">{{ $value }}</a>
    @elseif($kind === 'phone')
      <a href="tel:{{ preg_replace('/\s+/', '', $value) }}">{{ $value }}</a>
    @else
      {{ $value }}
    @endif
  </p>
  @endforeach
</div>
@endif
@endif

@if($page->document_file || $page->external_link)
@php
  $docUrl = $page->document_file
      ? asset('grievance/documents/' . $page->document_file)
      : $page->external_link;
@endphp
<div class="grievance-doc">
  <a class="btn-default" href="{{ $docUrl }}" target="_blank" rel="noopener noreferrer">
    {{ $page->document_label ?: 'View Document' }}
  </a>
</div>
@endif

@if(trim(strip_tags($page->note ?? '')) !== '')
<div class="grievance-note">{!! $page->note !!}</div>
@endif
