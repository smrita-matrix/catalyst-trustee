{{-- Layout 5 — collapsible FY/period groups, four-up cards (Security Cover Certificate, DSDKL Updates) --}}
<section class="security-cover-certi-sec">
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
    @php $grpId = 'secu-grp-' . $loop->index; $open = $loop->first; @endphp
    <div class="row">
      <div class="col-sm-12">
        <div class="secu-cover-certi-head seco-cehe-mar-top {{ $open ? '' : 'collapsed' }}" data-toggle="collapse" data-target="#{{ $grpId }}" aria-expanded="{{ $open ? 'true' : 'false' }}">
          <div class="secu-cover-certi-left">
            <span class="secu-cover-certi-month-icon"><i class="fa fa-calendar"></i></span>
            <h3 class="secu-cover-certi-month-title">{{ trim((string) $period) !== '' ? $period : 'Documents' }}</h3>
          </div>
          <span class="secu-cover-certi-month-chevron"><i class="fa fa-chevron-down"></i></span>
        </div>

        <div id="{{ $grpId }}" class="collapse {{ $open ? 'in' : '' }}">
          <div class="row secu-cover-certi-cards-row">
            @foreach($items as $notice)
            @php $url = $notice->document_url; @endphp
            <div class="col-sm-3 col-xs-6">
              <{{ $url ? 'a' : 'div' }} class="secu-cover-certi-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
                <div class="secu-cover-certi-card-icon"><i class="fa fa-file-pdf-o"></i></div>
                <h4 class="secu-cover-certi-card-title">{{ $notice->title }}</h4>
                @if($url)
                <span class="secu-cover-certi-card-cta">View Document <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                @endif
              </{{ $url ? 'a' : 'div' }}>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    @empty
    @include('frontend.public-notice.layouts._empty')
    @endforelse
  </div>
</section>
