{{-- Layout 3 — flat notice cards with date + description (Auction Notices) --}}
<section class="auc-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
          <h2>{{ $category->page_title ?: $category->name }}</h2>
        </div>
      </div>
    </div>

    @include('frontend.public-notice.layouts._alert')

    <div class="row auc-cards-row">
      @forelse($notices as $notice)
      @php $url = $notice->document_url; @endphp
      <div class="col-sm-3 col-xs-6">
        <{{ $url ? 'a' : 'div' }} class="auc-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
          @if($notice->notice_date)<span class="auc-card-date">{{ $notice->notice_date }}</span>@endif
          <h4 class="auc-card-title">{{ $notice->title }}</h4>
          @if($notice->description)<p class="auc-card-desc">{{ $notice->description }}</p>@endif
          @if($url)<span class="auc-card-foot">PDF <i class="fa fa-arrow-circle-o-right"></i></span>@endif
        </{{ $url ? 'a' : 'div' }}>
      </div>
      @empty
      @include('frontend.public-notice.layouts._empty')
      @endforelse
    </div>
  </div>
</section>
