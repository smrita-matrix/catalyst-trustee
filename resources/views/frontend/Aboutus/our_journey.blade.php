
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
      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('about-us/our-journey/banner/' . $banner->background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ optional($banner)->title ?? 'Our Journey' }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_parent ?? 'About' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->title ?? 'Our Journey' }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="our-journey-one-main-custom-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>Milestones in Progress</h2>
              </div>
            </div>
            <div class="col-md-12">
              <div class="our-journey-custom-timeline">
                @foreach($milestones as $index => $milestone)
                <div class="timeline" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ ($index % 2) * 150 }}">
                  <div class="timeline-content">
                    @if($milestone->icon_image)
                    <div class="timeline-icon">
                      <img src="{{ asset('about-us/our-journey/milestones/' . $milestone->icon_image) }}" alt="">
                    </div>
                    @endif
                    <span class="timeline-year">{{ $milestone->year }}</span>
                    @foreach(preg_split('/\R+/', trim($milestone->description ?? '')) as $para)
                      @if(trim($para) !== '')<p class="description">{{ trim($para) }}</p>@endif
                    @endforeach
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </section>
      @include('components.frontend.footer')
    </div>
  </div>
    @include('components.frontend.main-js')
</body>

</html>