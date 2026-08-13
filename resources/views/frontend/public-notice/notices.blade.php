<!DOCTYPE html>
<html lang="en">

<head>
 @include('components.frontend.head')
</head>

<body>
  <div class="body-overlay"></div>
  <header>
      @include('components.frontend.header')
    </header>

  <div id="smooth-wrapper">
    <div id="smooth-content">

      @php $bTitle = optional($banner)->title ?: 'Notices & Announcements'; @endphp
      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('public-notice/banner/'.$banner->background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $bTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_parent ?: 'Public Notice' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_child ?: $bTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {{-- ============ BREACH OF MINIMUM SECURITY COVER ============ --}}
      @if($bomsc->flatten()->count())
      <section class="bomsc-section">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>Breach of Minimum Security Cover</h2>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="bomsc-alert">
                <div class="bomsc-alert-icon"><i class="fa fa-bullhorn"></i></div>
                <div class="bomsc-alert-body">
                  <p class="bomsc-alert-heading">Attention Investors!</p>
                  <p class="bomsc-alert-text">
                    If the link for the scheduled breach of minimum security cover meeting is not received,
                    please write to us at
                    <a href="mailto:response.dt@ctltrustee.com">response.dt@ctltrustee.com</a>
                  </p>
                </div>
              </div>
            </div>
          </div>

          @foreach($bomsc as $period => $items)
          @if(trim((string)$period) !== '')
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
          @endforeach
        </div>
      </section>
      @endif

      {{-- ============ BREACH OF COVENANTS ============ --}}
      @if($boc->flatten()->count())
      <section class="boc-section">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>Breach Of Covenants</h2>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="boc-alert">
                <div class="boc-alert-icon"><i class="fa fa-bullhorn"></i></div>
                <div class="boc-alert-body">
                  <p class="boc-alert-heading">Attention Investors!</p>
                  <p class="boc-alert-text">
                    If the link for the scheduled breach of covenant meeting is not received,
                    please write to us at
                    <a href="mailto:BOC_Team@ctltrustee.com">BOC_Team@ctltrustee.com</a>
                  </p>
                </div>
              </div>
            </div>
          </div>

          @foreach($boc as $period => $items)
          @php $bocId = 'boc-grp-' . $loop->index; $open = $loop->first; @endphp
          <div class="row">
            <div class="col-sm-12">
              <div class="boc-month-head {{ $open ? '' : 'collapsed' }}" data-toggle="collapse" data-target="#{{ $bocId }}" aria-expanded="{{ $open ? 'true' : 'false' }}">
                <div class="boc-month-left">
                  <span class="boc-month-icon"><i class="fa fa-calendar"></i></span>
                  <h3 class="boc-month-title">{{ $period !== '' ? $period : 'Notices' }}</h3>
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
          @endforeach
        </div>
      </section>
      @endif

      {{-- ============ AUCTION NOTICES ============ --}}
      @if($auc->count())
      <section class="auc-section">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>Auction Notices</h2>
              </div>
            </div>
          </div>
          <div class="row auc-cards-row">
            @foreach($auc as $notice)
            @php $url = $notice->document_url; @endphp
            <div class="col-sm-3 col-xs-6">
              <{{ $url ? 'a' : 'div' }} class="auc-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
                @if($notice->notice_date)<span class="auc-card-date">{{ $notice->notice_date }}</span>@endif
                <h4 class="auc-card-title">{{ $notice->title }}</h4>
                @if($notice->description)<p class="auc-card-desc">{{ $notice->description }}</p>@endif
                @if($url)<span class="auc-card-foot">PDF <i class="fa fa-arrow-circle-o-right"></i></span>@endif
              </{{ $url ? 'a' : 'div' }}>
            </div>
            @endforeach
          </div>
        </div>
      </section>
      @endif

      @include('components.frontend.footer')
    </div>
  </div>
       @include('components.frontend.main-js')
</body>

</html>
