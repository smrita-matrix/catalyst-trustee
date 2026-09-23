
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

  @php $imgBase = 'service-uploads/securitisation/'; @endphp

  <div id="smooth-wrapper">
    <div id="smooth-content">

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($page)->banner_background_image) style="background-image: url('{{ asset($imgBase.'banner/'.$page->banner_background_image) }}');" @endif></div>
        <div class="container">
          @php $pageTitle = optional($page)->banner_title ?: optional($product ?? null)->name; @endphp
          <div class="breadcrumb-header-inner">
            <h1>{{ $pageTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                @if(optional($page)->banner_breadcrumb_parent)
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $page->banner_breadcrumb_parent }}</li>
                @endif
                @php $crumbChild = optional($page)->banner_breadcrumb_child ?: optional(optional($product ?? null)->serviceCategory)->name; @endphp
                @if($crumbChild)
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $crumbChild }}</li>
                @endif
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $pageTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {{-- The opening band: a picture beside the words that introduce the page. --}}
      @include('frontend.services.layouts.partials._securitisation-band', ['band' => 'intro'])

      {{-- The full-width panel: a picture on one side, headed paragraphs on the
           terracotta beside it, with a list underneath where there is one. --}}
      @php
        $panelBlocks = collect(optional($page)->panel_blocks ?? [])
            ->filter(fn ($b) => trim(strip_tags(($b['heading'] ?? '') . ($b['description'] ?? ''))) !== '');
        $panelPoints = trim(strip_tags(optional($page)->panel_points ?? ''));
      @endphp
      @if($panelBlocks->count() || $panelPoints !== '')
      @php $panelLeft = (optional($page)->panel_image_side ?? 'left') !== 'right'; @endphp
      <section class="technology-at-the-core-cus-sec">
        <div class="row no-gutter">
          @if(optional($page)->panel_image && $panelLeft)
          <div class="col-md-6 col-sm-12 no-padding">
            <div class="admission-img"><img src="{{ asset($imgBase.'panel/'.$page->panel_image) }}" alt=""></div>
          </div>
          @endif
          <div class="{{ optional($page)->panel_image ? 'col-md-6' : 'col-md-12' }} col-sm-12 no-padding">
            <div class="admission-content">
              <div class="inner">
                @foreach($panelBlocks as $i => $block)
                <div class="basp-ex-inner-sec-{{ $i === 0 ? 'one' : 'two' }}">
                  @if(trim(strip_tags($block['heading'] ?? '')) !== '')
                  <div class="heading heading-white" data-aos="fade-up" data-aos-duration="1600">
                    <h3>{{ $block['heading'] }}</h3>
                  </div>
                  @endif
                  {!! $block['description'] ?? '' !!}
                </div>
                @endforeach
                @if($panelPoints !== '')
                <ul class="debenture-trustee-listing-sec">
                  {!! preg_replace('#</?ul[^>]*>#i', '', optional($page)->panel_points) !!}
                </ul>
                @endif
              </div>
            </div>
          </div>
          @if(optional($page)->panel_image && !$panelLeft)
          <div class="col-md-6 col-sm-12 no-padding">
            <div class="admission-img"><img src="{{ asset($imgBase.'panel/'.$page->panel_image) }}" alt=""></div>
          </div>
          @endif
        </div>
      </section>
      @endif

      {{-- "Our Experience at a Glance": the numbers that describe the practice. --}}
      @php $glance = collect(optional($page)->glance_cards ?? [])->filter(fn ($c) => trim(($c['value'] ?? '') . ($c['label'] ?? '')) !== ''); @endphp
      @if($glance->count())
      <section class="our-experience-glance-custom-sec">
        <div class="container-fluid">
          @if(trim(strip_tags(optional($page)->glance_heading ?? '')) !== '')
          <div class="row">
            <div class="col-md-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1400">
                <h2>{{ $page->glance_heading }}</h2>
              </div>
            </div>
          </div>
          @endif
          <div class="our-experience-glance-two-custom-card-sec">
            <div class="row our-exp-gla-row-sec">
              @foreach($glance as $i => $card)
              <div class="col-md-3">
                <div class="our-exp-gla-col-sec" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ ($i % 4) * 150 }}">
                  @if(!empty($card['icon']))<img src="{{ asset($imgBase.'glance/'.$card['icon']) }}" alt="">@endif
                  <h4>{{ $card['value'] ?? '' }}</h4>
                  <p>{{ $card['label'] ?? '' }}</p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
      @endif

      {{-- The middle band, where the design puts the business description. --}}
      @include('frontend.services.layouts.partials._securitisation-band', ['band' => 'business'])

      {{-- The tabbed capabilities panel. --}}
      @php $tabs = collect(optional($page)->capability_tabs ?? [])->filter(fn ($t) => trim(($t['title'] ?? '')) !== '')->values(); @endphp
      @if($tabs->count())
      <section class="our-listed-ptc-capabilities-sec" id="aif-services">
        <div class="container">
          @if(trim(strip_tags(optional($page)->capabilities_heading ?? '')) !== '')
          <div class="row">
            <div class="col-md-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1400">
                <h2>{{ $page->capabilities_heading }}</h2>
              </div>
            </div>
          </div>
          @endif
          <div class="row or-lstd-cap-body">
            <div class="col-md-3 or-lstd-cap-nav-col">
              <div class="or-lstd-cap-nav-frame">
                <ul class="nav nav-pills nav-stacked or-lstd-cap-pillnav" role="tablist">
                  @foreach($tabs as $i => $tab)
                  <li role="presentation" class="{{ $loop->first ? 'active' : '' }}">
                    <a href="#captab-{{ $i }}" role="tab" data-toggle="tab">{{ $tab['title'] }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-md-9 or-lstd-cap-card-col">
              <div class="tab-content or-lstd-cap-tab-content">
                @foreach($tabs as $i => $tab)
                <div role="tabpanel" class="tab-pane fade {{ $loop->first ? 'in active' : '' }}" id="captab-{{ $i }}">
                  <div class="or-lstd-cap-pane-grid">
                    <div class="or-lstd-cap-pane-copy">
                      <div class="or-lstd-cap-pane-title-img-sec">
                        @if(!empty($tab['icon']))<img src="{{ asset($imgBase.'capabilities/'.$tab['icon']) }}" alt="">@endif
                        <h3 class="ou-li-ptc-cap-panel-head">{{ $tab['title'] }}</h3>
                      </div>
                      @if(trim(strip_tags($tab['description'] ?? '')) !== '')
                      <p class="or-lstd-cap-panel-copy">{{ strip_tags($tab['description']) }}</p>
                      @endif
                      @php $steps = collect(preg_split('/\R+/', trim($tab['flow'] ?? '')))->map(fn ($s) => trim($s))->filter()->values(); @endphp
                      @if($steps->count())
                      <div class="or-lstd-progs-sec">
                        <div class="process-flow">
                          @foreach($steps as $step)
                          <div class="process-step">{{ $step }}</div>
                          @if(!$loop->last)<div class="process-arrow"><i class="fa fa-long-arrow-right"></i></div>@endif
                          @endforeach
                        </div>
                      </div>
                      @endif
                      @if(trim(strip_tags($tab['note'] ?? '')) !== '')
                      <p class="or-lstd-cap-panel-copy">{{ strip_tags($tab['note']) }}</p>
                      @endif
                      @if(trim(strip_tags($tab['points'] ?? '')) !== '')
                      <ul class="debenture-trustee-listing-sec">
                        {!! preg_replace('#</?ul[^>]*>#i', '', $tab['points']) !!}
                      </ul>
                      @endif
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif

      {{-- The lifecycle strip, which slides on a narrow screen. --}}
      @php $steps = collect(optional($page)->lifecycle_steps ?? [])->filter(fn ($s) => trim(($s['title'] ?? '')) !== '')->values(); @endphp
      @if($steps->count())
      <section class="ptc-lifecycle-sec">
        <div class="container">
          @if(trim(strip_tags(optional($page)->lifecycle_heading ?? '')) !== '')
          <div class="row">
            <div class="col-md-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1400">
                <h2>{{ $page->lifecycle_heading }}</h2>
              </div>
            </div>
          </div>
          @endif
          <div class="ptc-lifecycle-carousel owl-carousel owl-theme">
            @foreach($steps as $step)
            <div class="ptc-life-slide">
              <div class="ptc-life-card">
                <div class="ptc-life-icon"><i class="fa {{ $step['icon'] ?: 'fa-cogs' }}"></i></div>
                {{-- The chain of arrows stops at the last step. --}}
                @if(!$loop->last)<div class="ptc-life-arrow"><i class="fa fa-long-arrow-right"></i></div>@endif
                <div class="ptc-life-content">
                  <h4>{{ $step['title'] }}</h4>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="ptc-life-controls">
            <button type="button" class="ptc-life-prev"><i class="fa fa-angle-left"></i></button>
            <button type="button" class="ptc-life-next"><i class="fa fa-angle-right"></i></button>
          </div>
        </div>
      </section>
      @endif

      {{-- The closing band, which the design sets on white. --}}
      @include('frontend.services.layouts.partials._securitisation-band', ['band' => 'closing'])

      @include('components.frontend.service-disclaimer')
      @include('components.frontend.footer')
    </div>
  </div>

  @include('components.frontend.main-js')
  @include('frontend.services.layouts.partials._securitisation-js')
</body>

</html>
