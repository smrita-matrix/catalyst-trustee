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

  @php $imgBase = 'services/fif/'; @endphp

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
      @php $hasIntroImg = (bool) optional($page)->intro_image; @endphp
      <section class="family-investment-funds-one-custom-sec">
        <div class="container">
          <div class="row">
            @if($hasIntroImg)
            <div class="col-md-6">
              <div class="family-investment-funds-one-img-cust-sec">
                <img src="{{ asset($imgBase.'intro/'.$page->intro_image) }}" alt="">
              </div>
            </div>
            @endif
            <div class="{{ $hasIntroImg ? 'col-md-6' : 'col-md-12' }}">
              <div class="heading family-investment-funds-one-content-sec" data-aos="fade-up" data-aos-duration="1000">
                @if(optional($page)->intro_subheading)<h4>{{ $page->intro_subheading }}</h4>@endif
                {!! optional($page)->intro_description !!}
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Definition / Concept -->
      @php
        $defHtml = trim((string) optional($page)->definition_description);
        $hasDefImg = (bool) optional($page)->definition_image;
      @endphp
      @if($defHtml !== '' || $hasDefImg || (optional($page)->definition_cards && count($page->definition_cards)))
      <section class="fam-inv-fu-ad-sec">
        <div class="row no-gutter">
          @if($hasDefImg)
          <div class="col-md-6 col-sm-12 no-padding">
            <div class="fam-inv-fu-ad-img">
              <img src="{{ asset($imgBase.'definition/'.$page->definition_image) }}" alt="Image">
            </div>
          </div>
          @endif
          <div class="{{ $hasDefImg ? 'col-md-6' : 'col-md-12' }} col-sm-12 no-padding">
            <div class="fam-inv-fu-ad-content">
              <div class="fam-inv-fu-ad-inner">
                @if($defHtml !== '')
                  {!! $defHtml !!}
                @else
                  @foreach(optional($page)->definition_cards ?? [] as $i => $card)
                  <div class="fifa-inner-card-{{ $i + 1 }}">
                    <h3>{{ $card['heading'] ?? '' }}</h3>
                    {!! nl2br(e($card['content'] ?? '')) !!}
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif

      <!-- Process tabs -->
      @php $tabs = optional($page)->process_tabs ?? []; @endphp
      @if(count($tabs))
      <section class="aif-services" id="aif-services">
        <div class="container">
          @if(trim(strip_tags(optional($page)->process_heading ?? '')) !== '')
          <div class="srv-divider fade-in visible" data-aos="fade-up" data-aos-duration="1000">
            <div class="srv-divider-label">{{ $page->process_heading }}</div>
            <div class="srv-divider-line"></div>
          </div>
          @endif
          <div class="row aif-body">
            <div class="col-md-3 aif-nav-col">
              <div class="aif-nav-frame">
                <ul class="nav nav-pills nav-stacked aif-pillnav" role="tablist">
                  @foreach($tabs as $i => $tab)
                  <li role="presentation" class="{{ $loop->first ? 'active' : '' }}">
                    <a href="#fiftab-{{ $i }}" role="tab" data-toggle="tab">{{ $tab['title'] ?? '' }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-md-9 aif-card-col">
              <div class="tab-content aif-tab-content">
                @foreach($tabs as $i => $tab)
                <div role="tabpanel" class="tab-pane fade {{ $loop->first ? 'in active' : '' }}" id="fiftab-{{ $i }}">
                  <div class="aif-pane-grid">
                    <div class="aif-pane-copy">
                      <div class="aif-pane-title-img-sec">
                        @if(!empty($tab['image']))<img src="{{ asset($imgBase.'process/'.$tab['image']) }}" alt="">@endif
                        <h3 class="aif-panel-head">{{ $tab['title'] ?? '' }}</h3>
                      </div>
                      @if(!empty($tab['description']))<p class="aif-panel-copy">{{ $tab['description'] }}</p>@endif
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

      <!-- Tax comparison -->
      @if(optional($page)->tax_intro || optional($page)->tax_table_html)
      <section class="aif-tax" id="fif-tax-comparison">
        <div class="container">
          @if(optional($page)->tax_intro)
          <div class="row">
            <div class="col-xs-12">
              <div class="aif-tax-para-sec">{!! $page->tax_intro !!}</div>
            </div>
          </div>
          @endif
          @if(optional($page)->tax_table_html)
          <div class="row">
            <div class="col-xs-12">
              <div class="aif-tax-card">
                <div class="aif-tax-scroll">
                  {!! $page->tax_table_html !!}
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </section>
      @endif

      <!-- Family Office Solution -->
      @if(optional($page)->family_heading || optional($page)->family_description || optional($page)->family_image)
      @php $hasFamilyImg = (bool) optional($page)->family_image; @endphp
      <section class="fif-family">
        <div class="container">
          <div class="row align-items-center">
            <div class="{{ $hasFamilyImg ? 'col-md-6' : 'col-md-12' }}">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->family_heading }}</h2>
                {!! optional($page)->family_description !!}
              </div>
            </div>
            @if($hasFamilyImg)
            <div class="col-md-6">
              <div class="fif-family-exhibit">
                <div class="fif-image-plate"><img src="{{ asset($imgBase.'family/'.$page->family_image) }}" alt=""></div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </section>
      @endif

      <!-- Capabilities -->
      @if(optional($page)->capabilities_heading || optional($page)->capabilities_points || optional($page)->capabilities_image)
      @php $hasCapImg = (bool) optional($page)->capabilities_image; @endphp
      <section class="securatization-listed-ptc-key-fact-sec">
        <div class="container-fluid">
          <div class="securatization-listed-ptc-key-fact-card-sec">
            <div class="row">
              @if($hasCapImg)
              <div class="col-md-6">
                <div class="securatization-listed-ptc-key-fact-card-img-sec">
                  <img src="{{ asset($imgBase.'capabilities/'.$page->capabilities_image) }}" alt="">
                </div>
              </div>
              @endif
              <div class="{{ $hasCapImg ? 'col-md-6' : 'col-md-12' }}">
                <div class="securatization-listed-ptc-key-fact-card-content-sec">
                  <h3>{{ optional($page)->capabilities_heading }}</h3>
                  <ul class="debenture-trustee-listing-sec">
                    {!! preg_replace('#</?ul[^>]*>#i', '', optional($page)->capabilities_points) !!}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif

      @if(trim(strip_tags(optional($page)->capabilities_disclaimer ?? '')) !== '')
      <section class="fif-disclaimer-sec">
        <div class="container">
          <div class="fif-disclaimer">
            <span class="fif-disclaimer-icon"><i class="glyphicon glyphicon-record"></i></span>
            <div class="fif-disclaimer-body">
              <strong>Disclaimer:</strong>
              {!! $page->capabilities_disclaimer !!}
            </div>
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
