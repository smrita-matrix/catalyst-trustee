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

  @php $imgBase = 'services/layout2/'; @endphp

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

      <!-- Nature Of Work -->
      <section class="securatization-listed-ptc-one-custom-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="securatization-listed-ptc-one-img-cust-sec">
                @if(optional($page)->nature_image)
                <img src="{{ asset($imgBase.'nature/'.$page->nature_image) }}" alt="">
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->nature_heading }}</h2>
                {!! optional($page)->nature_description !!}
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Process & Execution -->
      @if(optional($page)->process_heading || optional($page)->process_points || optional($page)->process_image)
      <section class="securatization-listed-ptc-process-admission-sec">
        <div class="row no-gutter">
          <div class="col-md-6 col-sm-12 no-padding">
            <div class="admission-img">
              @if(optional($page)->process_image)
              <img src="{{ asset($imgBase.'process/'.$page->process_image) }}" alt="Process & Execution">
              @endif
            </div>
          </div>
          <div class="col-md-6 col-sm-12 no-padding">
            <div class="admission-content">
              <div class="inner">
                <div class="heading heading-white" data-aos="fade-up" data-aos-duration="1600">
                  <h2>{{ optional($page)->process_heading }}</h2>
                </div>
                <ul class="debenture-trustee-listing-sec">
                  {!! preg_replace('#</?ul[^>]*>#i', '', optional($page)->process_points) !!}
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif

      <!-- Key Facts -->
      @if(optional($page)->keyfacts_heading || optional($page)->keyfacts_points || optional($page)->keyfacts_image || trim(strip_tags(optional($page)->keyfacts_note ?? '')) !== '')
      <section class="securatization-listed-ptc-key-fact-sec">
        <div class="container-fluid">
          <div class="securatization-listed-ptc-key-fact-card-sec">
            <div class="row">
              <div class="col-md-6">
                <div class="securatization-listed-ptc-key-fact-card-img-sec">
                  @if(optional($page)->keyfacts_image)
                  <img src="{{ asset($imgBase.'keyfacts/'.$page->keyfacts_image) }}" alt="">
                  @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="securatization-listed-ptc-key-fact-card-content-sec">
                  <h3>{{ optional($page)->keyfacts_heading }}</h3>
                  <ul class="debenture-trustee-listing-sec">
                    {!! preg_replace('#</?ul[^>]*>#i', '', optional($page)->keyfacts_points) !!}
                  </ul>
                </div>
              </div>
            </div>
          </div>
          @if(trim(strip_tags(optional($page)->keyfacts_note ?? '')) !== '')
          <div class="services-3-certificate-note-sec">
            <div class="row">
              <div class="col-xs-12">
                <div class="ser-three-note-strip-sec">
                  <span class="ser-three-note-icon"><i class="glyphicon glyphicon-record"></i></span>
                  {!! $page->keyfacts_note !!}
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
