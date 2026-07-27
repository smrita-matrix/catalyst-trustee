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

  @php $imgBase = 'services/layout3/'; @endphp

  <div id="smooth-wrapper">
    <div id="smooth-content">

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($page)->banner_background_image) style="background-image: url('{{ asset($imgBase.'banner/'.$page->banner_background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $product->name }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                @if(optional($page)->banner_breadcrumb_parent)
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $page->banner_breadcrumb_parent }}</li>
                @endif
                @php $crumbChild = optional($page)->banner_breadcrumb_child ?: optional($product->serviceCategory)->name; @endphp
                @if($crumbChild)
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $crumbChild }}</li>
                @endif
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $product->name }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- Intro -->
      @if(trim(strip_tags(optional($page)->intro_heading ?? '')) !== '' || trim(strip_tags(optional($page)->intro_description ?? '')) !== '' || optional($page)->intro_image)
      @php $hasIntroImg = (bool) optional($page)->intro_image; @endphp
      <section class="services-three-page-one-custom-sec">
        <div class="container">
          <div class="row">
            @if($hasIntroImg)
            <div class="col-md-6">
              <div class="services-three-page-one-img-cust-sec">
                <img src="{{ asset($imgBase.'intro/'.$page->intro_image) }}" alt="">
              </div>
            </div>
            @endif
            <div class="{{ $hasIntroImg ? 'col-md-6' : 'col-md-12' }}">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->intro_heading }}</h2>
                {!! optional($page)->intro_description !!}
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif

      <!-- Services tabs -->
      @php $tabs = optional($page)->services_tabs ?? []; @endphp
      @if(count($tabs))
      <section class="aif-services" id="aif-services">
        <div class="container">
          @if(optional($page)->services_divider_label)
          <div class="srv-divider fade-in visible" data-aos="fade-up" data-aos-duration="1000">
            <div class="srv-divider-label">{{ $page->services_divider_label }}</div>
            <div class="srv-divider-line"></div>
          </div>
          @endif
          <div class="row aif-body">
            <div class="col-md-3 aif-nav-col">
              <div class="aif-nav-frame">
                <ul class="nav nav-pills nav-stacked aif-pillnav" role="tablist">
                  @foreach($tabs as $i => $tab)
                  <li role="presentation" class="{{ $loop->first ? 'active' : '' }}">
                    <a href="#l3tab-{{ $i }}" role="tab" data-toggle="tab">{{ $tab['title'] ?? '' }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-md-9 aif-card-col">
              <div class="tab-content aif-tab-content">
                @foreach($tabs as $i => $tab)
                <div role="tabpanel" class="tab-pane fade {{ $loop->first ? 'in active' : '' }}" id="l3tab-{{ $i }}">
                  <div class="aif-pane-grid">
                    <div class="aif-pane-copy">
                      <div class="aif-pane-title-img-sec">
                        @if(!empty($tab['icon']))<img src="{{ asset($imgBase.'services/'.$tab['icon']) }}" alt="">@endif
                        <h3 class="aif-panel-head">{{ $tab['title'] ?? '' }}</h3>
                      </div>
                      @if(!empty($tab['description']))
                      <p class="aif-panel-copy">{{ $tab['description'] }}</p>
                      @endif
                      <ul class="debenture-trustee-listing-sec">
                        {!! preg_replace('#</?ul[^>]*>#i', '', $tab['points'] ?? '') !!}
                      </ul>
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

      <!-- Key Benefits -->
      @if(optional($page)->benefits_heading || optional($page)->benefits_points || optional($page)->benefits_image || trim(strip_tags(optional($page)->benefits_note ?? '')) !== '')
      @php $hasBenefitsImg = (bool) optional($page)->benefits_image; @endphp
      <section class="securatization-listed-ptc-key-fact-sec">
        <div class="container-fluid">
          <div class="securatization-listed-ptc-key-fact-card-sec">
            <div class="row">
              @if($hasBenefitsImg)
              <div class="col-md-6">
                <div class="securatization-listed-ptc-key-fact-card-img-sec">
                  <img src="{{ asset($imgBase.'benefits/'.$page->benefits_image) }}" alt="">
                </div>
              </div>
              @endif
              <div class="{{ $hasBenefitsImg ? 'col-md-6' : 'col-md-12' }}">
                <div class="securatization-listed-ptc-key-fact-card-content-sec">
                  <h3>{{ optional($page)->benefits_heading }}</h3>
                  <ul class="debenture-trustee-listing-sec">
                    {!! preg_replace('#</?ul[^>]*>#i', '', optional($page)->benefits_points) !!}
                  </ul>
                </div>
              </div>
            </div>
          </div>
          @if(optional($page)->benefits_note)
          <div class="services-3-certificate-note-sec">
            <div class="row">
              <div class="col-xs-12">
                <div class="ser-three-note-strip-sec">
                  <span class="ser-three-note-icon"><i class="glyphicon glyphicon-record"></i></span>
                  {!! $page->benefits_note !!}
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </section>
      @endif

      @include('components.frontend.footer')
    </div>
  </div>
       @include('components.frontend.main-js')
</body>

</html>
