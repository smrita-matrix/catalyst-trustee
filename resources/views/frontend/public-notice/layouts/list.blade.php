{{-- Layout 4 — four-up card list with description (Revision in Credit Ratings, Policies, Investor Charter) --}}
<section class="credit-rating-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="heading" data-aos="fade-up" data-aos-duration="1000">
          <h2>{{ $category->page_title ?: $category->name }}</h2>
        </div>
      </div>
    </div>

    @include('frontend.public-notice.layouts._alert')

    <div class="row bomsc-cards-row">
      @forelse($notices as $notice)
      @php $url = $notice->document_url; @endphp
      <div class="col-sm-3 col-xs-12">
        <{{ $url ? 'a' : 'div' }} class="bomsc-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
          <div class="bomsc-card-icon"><i class="fa fa-calendar"></i></div>
          <h4 class="bomsc-card-title">{{ $notice->title }}</h4>
          @if($notice->description)<p class="bom-cre-rating-para">{{ $notice->description }}</p>@endif
          @if($url)
          <span class="bomsc-card-cta">View Document <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
          @endif
        </{{ $url ? 'a' : 'div' }}>
      </div>
      @empty
      @include('frontend.public-notice.layouts._empty')
      @endforelse
    </div>
  </div>
</section>
