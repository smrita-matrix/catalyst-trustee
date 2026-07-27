
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

  @php $imgBase = 'services/debenture-trustee-listed/'; @endphp

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

      @if(optional($page)->intro_image || trim(strip_tags(optional($page)->intro_heading ?? '')) !== '' || trim(strip_tags(optional($page)->intro_description ?? '')) !== '' || trim(strip_tags(optional($page)->intro_expertise_points ?? '')) !== '')
      @php $hasIntroImg = (bool) optional($page)->intro_image; @endphp
      <section class="debenture-trustee-one-custom-sec">
        <div class="container">
          <div class="row">
            @if($hasIntroImg)
            <div class="col-md-6">
              <div class="debenture-trustee-one-img-cust-sec">
                <img src="{{ asset($imgBase.'intro/'.$page->intro_image) }}" alt="">
              </div>
            </div>
            @endif
            <div class="{{ $hasIntroImg ? 'col-md-6' : 'col-md-12' }}">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->intro_heading }}</h2>
                {!! optional($page)->intro_description !!}
              </div>
              @if(optional($page)->intro_expertise_heading || trim(strip_tags(optional($page)->intro_expertise_points ?? '')) !== '')
              <div class="debenture-trustee-one-sub-para-cus-sec">
                <h4>{{ optional($page)->intro_expertise_heading }}</h4>
                <ul class="debenture-trustee-listing-main-sec">
                  {!! preg_replace('#</?ul[^>]*>#i', '', optional($page)->intro_expertise_points) !!}
                </ul>
              </div>
              @endif
            </div>
          </div>
        </div>
      </section>
      @endif

      @if(optional($page)->services_include_image || trim(strip_tags(optional($page)->services_include_heading ?? '')) !== '' || trim(strip_tags(optional($page)->services_include_points ?? '')) !== '')
      @php $hasSvcImg = (bool) optional($page)->services_include_image; @endphp
      <section class="rs-admission">
        <div class="row no-gutter">
          @if($hasSvcImg)
          <div class="col-md-6 col-sm-12 no-padding">
            <div class="admission-img">
              <img src="{{ asset($imgBase.'services-include/'.$page->services_include_image) }}" alt="">
            </div>
          </div>
          @endif
          <div class="{{ $hasSvcImg ? 'col-md-6' : 'col-md-12' }} col-sm-12 no-padding">
            <div class="admission-content">
              <div class="inner">
                <div class="heading heading-white" data-aos="fade-up" data-aos-duration="1600">
                  <h2>{{ optional($page)->services_include_heading }}</h2>
                </div>
                <div class="debenture-trustee-listing-wrap">
                  {!! preg_replace('#<ul(\s[^>]*)?>#i', '<ul class="debenture-trustee-listing-sec">', optional($page)->services_include_points) !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif

      @if(optional($page)->why_cards && count($page->why_cards))
      <section class="debenture-trustee-two-custom-sec">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1400">
                <h2>{{ optional($page)->why_heading }}</h2>
              </div>
            </div>
          </div>
          <div class="debenture-trustee-two-custom-card-sec">
            <div class="row dttcc-row-sec">
              @foreach(optional($page)->why_cards ?? [] as $card)
              <div class="col-md-2 dttcc-col-sec">
                <div class="de-tr-tw-cus-col-sec">
                  @if(!empty($card['icon']))
                  <img src="{{ asset($imgBase.'why/'.$card['icon']) }}" alt="">
                  @endif
                  <h4>{{ $card['title'] ?? '' }}</h4>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
      @endif

      @php
        $offeredTabs = collect(optional($page)->services_offered_tabs ?? [])->filter(function ($t) {
            return !empty($t['title']) || !empty($t['image']) || trim(strip_tags($t['points'] ?? '')) !== '';
        })->values();
        $showTabNav = $offeredTabs->count() > 1;
      @endphp
      @if($offeredTabs->count())
      <section class="debenture-trustee-services-offered-sec">
        <div class="container-fluid">
          @if(trim(strip_tags(optional($page)->services_offered_heading ?? '')) !== '')
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->services_offered_heading }}</h2>
              </div>
            </div>
          </div>
          @endif

          <div class="debenture-trustee-services-offered-tab-sec" role="tabpanel">

            @if($showTabNav)
            <ul class="nav nav-tabs" role="tablist">
              @foreach($offeredTabs as $i => $tab)
              <li class="{{ $loop->first ? 'active' : '' }}">
                <a href="#dt-tab-{{ $i }}" role="tab" data-toggle="tab">{{ $tab['title'] ?? '' }}</a>
              </li>
              @endforeach
            </ul>
            @endif

            <div class="tab-content">
              @foreach($offeredTabs as $i => $tab)
              <div class="tab-pane fade {{ $loop->first ? 'in active' : '' }}" id="dt-tab-{{ $i }}">
                @php $hasTabImg = !empty($tab['image']); @endphp
                <div class="row">
                  @if($hasTabImg)
                  <div class="col-md-6">
                    <div class="de-tru-services-offered-tab-card-img-sec">
                      <img src="{{ asset($imgBase.'services-offered/'.$tab['image']) }}" alt="">
                    </div>
                  </div>
                  @endif
                  <div class="{{ $hasTabImg ? 'col-md-6' : 'col-md-12' }}">
                    <div class="de-tru-services-offered-tab-card-content-sec">
                      @if(!empty($tab['title']))
                      <h3>{{ $tab['title'] }}</h3>
                      @endif
                      <div class="debenture-trustee-listing-wrap">
                        {!! preg_replace('#<ul(\s[^>]*)?>#i', '<ul class="debenture-trustee-listing-sec">', $tab['points'] ?? '') !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>

          </div>
        </div>
      </section>
      @endif

      @if(optional($page)->recognition_heading || (optional($page)->certificates && count($page->certificates)) || trim(strip_tags(optional($page)->recognition_note ?? '')) !== '')
      <section class="debenture-trustee-certificate-note-sec">
        <div class="container">
          @php $hasCerts = collect(optional($page)->certificates ?? [])->filter(fn($c) => !empty($c['image']))->count() > 0; @endphp
          @if($hasCerts && trim(strip_tags(optional($page)->recognition_heading ?? '')) !== '')
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->recognition_heading }}</h2>
              </div>
            </div>
          </div>
          @endif
          @if($hasCerts)
          <div class="row dtcn-row">
            @foreach(optional($page)->certificates ?? [] as $cert)
              @if(!empty($cert['image']))
              <div class="col-md-4 dtcn-col">
                <div class="dtcn-card">
                  <div class="dtcn-img-wrap">
                    <a href="{{ asset($imgBase.'certificates/'.$cert['image']) }}" data-lightbox="dtcn-certificates" class="dtcn-zoom-link">
                      <img src="{{ asset($imgBase.'certificates/'.$cert['image']) }}" alt="{{ $cert['alt'] ?? '' }}" class="img-responsive">
                      <span class="dtcn-zoom-icon"><i class="glyphicon glyphicon-zoom-in"></i></span>
                    </a>
                  </div>
                </div>
              </div>
              @endif
            @endforeach
          </div>
          @endif
          @if(optional($page)->recognition_note)
          <div class="row">
            <div class="col-xs-12">
              <div class="dtcn-note-strip">
                <span class="dtcn-note-icon"><i class="glyphicon glyphicon-record"></i></span>
                {!! $page->recognition_note !!}
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