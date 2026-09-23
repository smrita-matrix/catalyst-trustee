{{-- One of the three bands that are a picture beside some words.

     The design uses this shape three times on the Listed page and once on
     the Unlisted one, flipping the picture from one side to the other and
     dropping the tint on the last of them, so which side and which ground
     are settings rather than three separate blocks of markup. --}}
@php
    $img   = optional($page)->{$band . '_image'};
    $head  = trim(strip_tags(optional($page)->{$band . '_heading'} ?? ''));
    $sub   = trim(strip_tags(optional($page)->{$band . '_subheading'} ?? ''));
    $body  = trim(strip_tags(optional($page)->{$band . '_description'} ?? ''));
    $left  = (optional($page)->{$band . '_image_side'} ?? 'left') !== 'right';
    $white = (optional($page)->{$band . '_background'} ?? '') === 'white';
    $sec   = $band === 'intro'
        ? 'end-to-end-securitisation-one-custom-sec'
        : 'listed-securitisation-business-custom-sec';
    $imgWrap = $band === 'intro'
        ? 'end-to-end-securitisation-one-img-cust-sec'
        : 'listed-securitisation-business-img-cust-sec';
@endphp

@if($img || $head !== '' || $sub !== '' || $body !== '')
<section class="{{ $sec }}{{ $white ? ' bg-white' : '' }}">
  <div class="container">
    <div class="row">
      @if($img && $left)
      <div class="col-md-6">
        <div class="{{ $imgWrap }}">
          <img src="{{ asset($imgBase . $band . '/' . $img) }}" alt="">
        </div>
      </div>
      @endif
      <div class="{{ $img ? 'col-md-6' : 'col-md-12' }}">
        <div class="heading" data-aos="fade-up" data-aos-duration="1000">
          @if($head !== '')<h2>{{ optional($page)->{$band . '_heading'} }}</h2>@endif
          @if($sub !== '')<h4>{{ optional($page)->{$band . '_subheading'} }}</h4>@endif
          {!! optional($page)->{$band . '_description'} !!}
        </div>
      </div>
      @if($img && !$left)
      <div class="col-md-6">
        <div class="{{ $imgWrap }}">
          <img src="{{ asset($imgBase . $band . '/' . $img) }}" alt="">
        </div>
      </div>
      @endif
    </div>
  </div>
</section>
@endif
