{{-- Layout 1 — date pill groups, two-up cards (Breach of Minimum Security Cover) --}}
<section class="bomsc-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="heading" data-aos="fade-up" data-aos-duration="1000">
          <h2>{{ $category->page_title ?: $category->name }}</h2>
        </div>
      </div>
    </div>

    @include('frontend.public-notice.layouts._alert')

    @forelse($grouped as $period => $items)
    @if(trim((string) $period) !== '')
    <div class="row">
      <div class="col-sm-12">
        <div class="bomsc-meta-row">
          <span class="bomsc-date"><i class="fa fa-calendar"></i> {{ $period }}</span>
          <span class="bomsc-meta-line"></span>
        </div>
      </div>
    </div>
    @endif

    <div class="row bomsc-cards-row">
      @foreach($items as $notice)
      @php $url = $notice->document_url; @endphp
      <div class="col-sm-6 col-xs-12">
        <{{ $url ? 'a' : 'div' }} class="bomsc-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
          <span class="bomsc-card-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
          <div class="bomsc-card-icon"><i class="fa fa-file-pdf-o"></i></div>
          <h4 class="bomsc-card-title">{{ $notice->title }}</h4>
          @if($url)
          <span class="bomsc-card-cta">View Document <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
          @endif
        </{{ $url ? 'a' : 'div' }}>
      </div>
      @endforeach
    </div>
    @empty
    @include('frontend.public-notice.layouts._empty')
    @endforelse
  </div>
</section>
