{{-- The note at the foot of a service page.

     Services SEBI does not regulate have to say so, and the same strip is
     used wherever that note appears, so every layout can include this and
     the ones with nothing to say render nothing at all. --}}
@php $note = trim(strip_tags(optional($product ?? null)->disclaimer ?? '')); @endphp
@if($note !== '')
<section class="service-disclaimer-sec">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="dtcn-note-strip">
          <span class="dtcn-note-icon"><i class="glyphicon glyphicon-record"></i></span>
          <p><strong>Disclaimer:</strong><br>{!! nl2br(e($product->disclaimer)) !!}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
