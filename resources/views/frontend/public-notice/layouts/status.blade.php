{{-- Layout 6 — flat FY boxes with description (Status of Payment of Interest & Principal) --}}
<section class="regulatory-disclosures-status-of-payment-sec">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="heading" data-aos="fade-up" data-aos-duration="1000">
          <h2>{{ $category->page_title ?: $category->name }}</h2>
        </div>
      </div>
    </div>

    @include('frontend.public-notice.layouts._alert')

    <div class="row regul-disclo-stpmt-box-row">
      @forelse($notices as $notice)
      @php $url = $notice->document_url; @endphp
      <div class="col-sm-3 col-xs-6">
        <{{ $url ? 'a' : 'div' }} class="regul-disclo-stpmt-box-col" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
          <div class="regul-disclo-stpmt-box-col-icon"><i class="fa fa-calendar"></i></div>
          <h4 class="regul-disclo-stpmt-box-col-title">{{ $notice->title }}</h4>
          @if($notice->description)<p>{{ $notice->description }}</p>@endif
          @if($url)
          <span class="regul-disclo-stpmt-box-col-cta">View Document <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
          @endif
        </{{ $url ? 'a' : 'div' }}>
      </div>
      @empty
      @include('frontend.public-notice.layouts._empty')
      @endforelse
    </div>
  </div>
</section>
