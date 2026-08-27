{{-- Layout 2 — collapsible month groups, four-up cards (Breach Of Covenants) --}}
<section class="boc-section">
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
    @php $bocId = 'boc-grp-' . $loop->index; $open = $loop->first; @endphp
    <div class="row">
      <div class="col-sm-12">
        <div class="boc-month-head {{ $open ? '' : 'collapsed' }}" data-toggle="collapse" data-target="#{{ $bocId }}" aria-expanded="{{ $open ? 'true' : 'false' }}">
          <div class="boc-month-left">
            <span class="boc-month-icon"><i class="fa fa-calendar"></i></span>
            <h3 class="boc-month-title">{{ trim((string) $period) !== '' ? $period : 'Notices' }}</h3>
          </div>
          <span class="boc-month-chevron"><i class="fa fa-chevron-down"></i></span>
        </div>

        <div id="{{ $bocId }}" class="collapse {{ $open ? 'in' : '' }}">
          <div class="row boc-cards-row">
            @foreach($items as $notice)
            @php $url = $notice->document_url; @endphp
            <div class="col-sm-3 col-xs-6">
              <{{ $url ? 'a' : 'div' }} class="boc-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
                <div class="boc-card-icon"><i class="fa fa-file-pdf-o"></i></div>
                <h4 class="boc-card-title">{{ $notice->title }}</h4>
                @if($url)
                <span class="boc-card-cta">View <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
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
