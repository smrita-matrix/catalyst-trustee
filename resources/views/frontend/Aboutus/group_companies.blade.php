
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
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('about-us/group-companies/banner/' . $banner->background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ optional($banner)->title ?? 'Group Companies' }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_parent ?? 'About' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->title ?? 'Group Companies' }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      @if($overview)
      <section class="catalyst-group-companies-one-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="cgc-image-wrapper">
                <div class="cgc-bg-box"></div>
                @if($overview->main_image)
                <div class="cgc-main-img">
                  <img src="{{ asset('about-us/group-companies/overview/' . $overview->main_image) }}" alt="">
                </div>
                @endif
                @if($overview->small_image)
                <div class="cgc-small-img">
                  <img src="{{ asset('about-us/group-companies/overview/' . $overview->small_image) }}" alt="">
                </div>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="cgc-content">
                <h2 data-aos="fade-up" data-aos-duration="1600">{{ $overview->heading }}</h2>
                {!! $overview->description !!}
              </div>
            </div>
          </div>
        </div>
      </section>
      @endif
      @if(optional($overview)->entities && count($overview->entities))
      <section class="catalyst-ctl-trusteeship-limited-sec">
        <div class="container">
          @foreach($overview->entities as $index => $entity)
            @if($index % 2 === 0)
            <div class="ctl-trusteeship-limited-main-row-sec" data-aos="fade-up" data-aos-duration="1600">
              <div class="row">
                <div class="col-md-3">
                  <div class="ctl-trusteeship-limited-img-col-sec">
                    @if(!empty($entity['image']))
                    <img src="{{ asset('about-us/group-companies/overview/' . $entity['image']) }}" alt="{{ $entity['title'] ?? '' }}">
                    @endif
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="ctl-trusteeship-limited-content-col-sec">
                    <h2 data-aos="fade-up" data-aos-duration="1600">{{ $entity['title'] ?? '' }}</h2>
                    @foreach(preg_split('/\R+/', trim($entity['description'] ?? '')) as $para)
                      @if(trim($para) !== '')<p>{{ trim($para) }}</p>@endif
                    @endforeach
                    @if(!empty($entity['link']))
                    <div class="ctl-trusteeship-limited-content-btn-sec">
                      <a class="btn-default btn-black" href="{{ $entity['link'] }}">Know More</a>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @else
            <div class="ctl-trusteeship-limited-main-row-two-sec" data-aos="fade-up" data-aos-duration="1600">
              <div class="row">
                <div class="col-md-9">
                  <div class="ctl-trusteeship-limited-two-content-col-sec">
                    <h2 data-aos="fade-up" data-aos-duration="1600">{{ $entity['title'] ?? '' }}</h2>
                    @foreach(preg_split('/\R+/', trim($entity['description'] ?? '')) as $para)
                      @if(trim($para) !== '')<p>{{ trim($para) }}</p>@endif
                    @endforeach
                    @if(!empty($entity['link']))
                    <div class="ctl-trusteeship-limited-content-btn-sec">
                      <a class="btn-default btn-black" href="{{ $entity['link'] }}">Know More</a>
                    </div>
                    @endif
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="ctl-trusteeship-limited-two-img-col-sec">
                    @if(!empty($entity['image']))
                    <img src="{{ asset('about-us/group-companies/overview/' . $entity['image']) }}" alt="{{ $entity['title'] ?? '' }}">
                    @endif
                  </div>
                </div>
              </div>
            </div>
            @endif
          @endforeach
        </div>
      </section>
      @endif
      @if($difc)
      <section class="catalyst-difc-services-limited-sec">
        <div class="container-fluid">
          <div class="cata-difc-services-main-sec">

            <div class="cata-difc-services-main-content-sec">
              @if($difc->logo_image)
              <img src="{{ asset('about-us/group-companies/difc/' . $difc->logo_image) }}" alt="">
              @endif
              <div class="heading heading-white" data-aos="fade-up" data-aos-duration="1600">
                <h2>{{ $difc->heading }}</h2>
              </div>
              {!! $difc->top_description !!}
            </div>
            @if($difc->services && count($difc->services))
            <div class="cata-difc-services-main-eight-col-sec">
              <div class="row">
                @foreach($difc->services as $i => $service)
                <div class="col-md-3">
                  <div class="cata-difc-service-box" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ ($i % 4) * 150 }}">
                    <div class="cata-difc-service-icon">
                      @if(!empty($service['icon']))
                      <img src="{{ asset('about-us/group-companies/difc/' . $service['icon']) }}" alt="">
                      @endif
                    </div>
                    <div class="cata-difc-service-content">
                      <h4>{{ $service['title'] ?? '' }}</h4>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif
            @if($difc->bottom_description)
            <div class="cata-difc-services-main-content-two-sec">
              {!! $difc->bottom_description !!}
            </div>
            @endif
            @if($difc->button_text)
            <div class="cata-difc-services-main-btn-sec">
              <a class="btn-default" href="{{ $difc->button_link ?? '#' }}">{{ $difc->button_text }}</a>
            </div>
            @endif
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