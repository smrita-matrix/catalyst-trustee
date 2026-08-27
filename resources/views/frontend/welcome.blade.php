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
        <section class="hero-banner">
          <div class="owl-carousel banner-slider">
            @forelse ($banners as $banner)
            <div class="banner-item">
              <img src="{{ $banner->banner_images ? asset('home/banner/' . $banner->banner_images) : asset('frontend/assets/images/banner/a.jpg') }}" alt="{{ strip_tags($banner->banner_heading) }}" />
              <div class="overlay"></div>
              <div class="banner-content">
                <h1>{!! preg_replace('#</?p[^>]*>#', '', $banner->banner_heading) !!}</h1>
                <h2>{!! preg_replace('#</?p[^>]*>#', '', $banner->banner_description) !!}</h2>
                @if ($banner->button_text)
                <a class="btn-default" href="{{ $banner->button_link ?: '#' }}">{{ $banner->button_text }}</a>
                @endif
              </div>
            </div>
            @empty
            <div class="banner-item">
              <img src="{{ asset('frontend/assets/images/banner/a.jpg')}}" alt="Financial Trusteeship Solutions" />
              <div class="overlay"></div>
              <div class="banner-content">
                <h1>Building Trust Through Structured <span>Financial & Trusteeship</span> Solutions</h1>
                <h2>Catalyst Trusteeship Limited delivers end-to-end trusteeship services in India, compliance management, governance frameworks, and fiduciary solutions tailored to evolving business and regulatory requirements.</h2>
                <a class="btn-default" href="#">Find Out More</a>
              </div>
            </div>
            @endforelse
          </div>
        </section>
        <div class="marquee-strip">
          <div class="marquee-inner">
            @foreach ($marquee as $item)
            <span>{{ $item->title }}</span>
            @endforeach
          </div>
        </div>
        <section class="counter-wrap" id="choose">
          <div class="container-fluid">
            <div class="row row-flex">
              <div class="col-md-6">
                <div class="row counter-flex-row">
                  @php $chooseExtra = ['', 'mT60', 'mT60minus', '']; @endphp
                  @foreach ($about?->features ?? [] as $i => $feature)
                  <div class="col-md-3 col-sm-6 col-xs-12 {{ $chooseExtra[$i % 4] ?? '' }}">
                    <div class="choose-box" data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ ($i % 4) * 150 }}">
                      <div class="choose-content">
                        <div class="choose-icon">
                          @if (!empty($feature['icon_svg']))
                            {!! $feature['icon_svg'] !!}
                          @elseif (!empty($feature['icon']))
                            <img src="{{ asset('home/about-catalyst/' . $feature['icon']) }}" alt="{{ $feature['title'] ?? '' }}">
                          @endif
                        </div>
                        <h4>{{ $feature['title'] ?? '' }}</h4>
                        <p>{{ $feature['description'] ?? '' }}</p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              <div class="col-md-6">
                <div class="about-text" data-aos="fade-up" data-aos-duration="2000">
                  <div class="heading">
                    <h6>{{ $about?->sub_heading }}</h6>
                    <h2>{{ $about?->heading }}</h2>
                  </div>
                  {!! $about?->description !!}
                  @if ($about?->button_text)
                  <a class="btn-default" href="{{ $about?->button_link ?: '#' }}">{{ $about->button_text }}</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="why-wrap cnt-portfolio-ptb">
          <div class="container-fluid">
            <div class="heading heading-white heading-center">
              <h2>{{ $whyChoose?->heading }}</h2>
            </div>
            <div class="row flex">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="cnt-portfolio-video-card-wrapper d-flex flex-column justify-content-center">
                  @foreach ($whyChoose?->items ?? [] as $item)
                  <div class="cnt-portfolio-video-card">
                    @if (!empty($item['icon_svg']))
                      {!! $item['icon_svg'] !!}
                    @elseif (!empty($item['icon']))
                      <img src="{{ asset('home/why-choose/' . $item['icon']) }}">
                    @endif
                    <h4 class="cnt-portfolio-video-title">{{ $item['text'] ?? '' }}</h4>
                  </div>
                  @endforeach
                </div>
              </div>
              <!-- <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="why-img sticky-img">
                  <img src="{{ asset('frontend/assets/images/home/why-img2.webp') }}" class="img-responsive">
                </div>
              </div> -->
            </div>
          </div>
        </section>
        <section class="all-services-wrap">
          <section class="sebi-services-wrap">
            <div class="container-fluid">
              <div class="srv-divider fade-in visible" data-aos="fade-up" data-aos-duration="1000">
                <div class="srv-divider-label">{{ $sebi?->heading }}</div>
                <div class="srv-divider-line"></div>
              </div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="sebi owl-carousel owl-theme">
                    @foreach ($sebi?->items ?? [] as $item)
                    <div class="item">
                      <div class="service-card2">
                        @if (!empty($item['service_img']))
                        <img src="{{ asset('home/sebi-services/' . $item['service_img']) }}" alt="{{ $item['title'] ?? '' }}" class="service-img">
                        @endif
                        <div class="service-content-wrap">
                          <div class="service-content" style="--collapsed-height: 95px;">
                            <div class="title-area">
                              <div class="icon">
                                @if (!empty($item['icon']))
                                <img src="{{ asset('home/sebi-services/' . $item['icon']) }}">
                                @endif
                              </div>
                              <h3><a href="{{ ($item['title_link'] ?? '') ?: '#' }}">{{ $item['title'] ?? '' }}</a></h3>
                            </div>
                            <div class="description-and-btn-area">
                              <p>{{ $item['description'] ?? '' }}</p>
                              <a href="{{ ($item['read_more_link'] ?? '') ?: '#' }}" class="read-more-btn">
                                <span>Read more</span>
                                <img src="{{ asset('frontend/assets/images/icons/right-arrow-bold.svg')}}">
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </section>
          <section class="nonsebi-services-wrap">
            <div class="container-fluid">
              <!-- <div class="heading heading-center">
                <h2>Non-SEBI Regulated Trustee Services </h2>
              </div> -->
              <div class="srv-divider fade-in visible" data-aos="fade-up" data-aos-duration="1000">
                <div class="srv-divider-label">{{ $nonSebi?->heading }}</div>
                <div class="srv-divider-line"></div>
              </div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="nonsebi owl-carousel owl-theme">
                    @foreach ($nonSebi?->items ?? [] as $item)
                    <div class="service-item style-1">
                      <div class="service-img">
                        @if (!empty($item['service_img']))
                        <img src="{{ asset('home/non-sebi-services/' . $item['service_img']) }}" alt="{{ $item['title'] ?? '' }}">
                        @endif
                      </div>
                      <div class="service-icon">
                        @if (!empty($item['icon']))
                        <img src="{{ asset('home/non-sebi-services/' . $item['icon']) }}" alt="">
                        @endif
                      </div>
                      <div class="service-content">
                        <h4 class="title"><a href="{{ ($item['title_link'] ?? '') ?: '#' }}">{{ $item['title'] ?? '' }}</a></h4>
                        <p class="desc">{{ $item['description'] ?? '' }}</p>
                        <a class="text-btn" href="{{ ($item['read_more_link'] ?? '') ?: '#' }}"><span class="btn-text"><span>Learn More <img src="{{ asset('frontend/assets/images/icons/right-arrow-bold-white.svg') }}"></span></span></a>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </section>
        </section>
        <section class="giftcity-services-wrap">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="heading heading-center heading-white" data-aos="fade-up" data-aos-duration="1600">
                  <h2>{{ $giftCity?->heading }}</h2>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                @foreach ($giftCity?->items ?? [] as $i => $item)
                <div class="project-item h11-project-item">
                  <div class="project-img">
                    @if (!empty($item['image']))
                    <img src="{{ asset('home/gift-city/' . $item['image']) }}" alt="" class="img-responsive">
                    @endif
                    <div class="project-sl">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}.</div>
                  </div>
                  <div class="project-content">
                    <div class="project-text">
                      <h3 class="title">
                        <a href="{{ ($item['title_link'] ?? '') ?: '#' }}">{{ $item['title'] ?? '' }}</a>
                      </h3>
                      <p>
                        {{ $item['description'] ?? '' }}
                      </p>
                    </div>
                    <a class="project-btn" href="{{ ($item['title_link'] ?? '') ?: '#' }}">
                      <img src="{{ asset('frontend/assets/images/icons/right-arrow.png')}}">
                    </a>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="giftcity-text">
                  <p>{{ $giftCity?->footer_text }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="leadership-wrap">
          <div class="container-fluid">
            <div class="row row-flex">
              <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="team-box">
                  <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1400">
                    <h2>{{ $leadership?->leadership_heading }}</h2>
                  </div>
                  <div class="team-list">
                    @foreach ($leadership?->leaders ?? [] as $i => $leader)
                    <div class="single-team">
                      <div class="team-thumb">
                        <a data-toggle="modal" data-target="#team{{ $i }}">
                          @if (!empty($leader['image']))
                          <img src="{{ asset('home/leadership/' . $leader['image']) }}" class="img-responsive" alt="image">
                          @endif
                        </a>
                      </div>
                      <div class="team-text-text">
                        <div class="team-content">
                          <h5><a data-toggle="modal" data-target="#team{{ $i }}">{{ $leader['name'] ?? '' }}</a></h5>
                          <span class="team-designation">{{ $leader['designation'] ?? '' }}</span>
                        </div>
                        <div class="team-anchor">
                          <a data-toggle="modal" data-target="#team{{ $i }}"><img src="{{ asset('frontend/assets/images/icons/right-arrow-bold.svg')}}"/></a>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
              <div class="col-md-7 col-sm-6 col-xs-12">
                <div class="counter-box-wrap">
                  <div class="counter-heading"><h2>{{ $leadership?->numbers_heading }}</h2></div>
                  <div class="counter-list">
                    @foreach ($leadership?->numbers ?? [] as $i => $num)
                    <div class="countup-item style-2">
                      <span class="count-icon">
                        @if (!empty($num['icon']))
                        <img src="{{ asset('home/leadership/' . $num['icon']) }}">
                        @endif
                      </span>
                      <span class="steps">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}.</span>
                      <div class="count-inner">
                        <span class="count-text">{{ $num['count_text'] ?? '' }}</span>
                        <div class="counter">
                          <span class="counter-number">{{ $num['number'] ?? '' }}</span> <span class="plus">{{ $num['suffix'] ?? '' }}</span>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="performance-wrap">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                  <h6>Catalyst delivers more than expected</h6>
                  <h2>Business Performance</h2>
                </div>
                <div class="graph">
                  <div id="chart_div" style="width:100%;"></div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="casestudy-section">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                  <h2>{{ $landmark?->heading }}</h2>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="swiper landmarkSwiper">
                  <div class="swiper-wrapper">
                    @foreach ($landmark?->items ?? [] as $i => $item)
                    <div class="swiper-slide single-casestudy">
                      <h2>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</h2>
                      @if (!empty($item['image']))
                      <img src="{{ asset('home/landmark/' . $item['image']) }}" alt="">
                      @endif
                      <h3>{{ $item['title'] ?? '' }}</h3>
                      <p>{{ $item['description'] ?? '' }}</p>
                      <a href="{{ ($item['link'] ?? '') ?: '#' }}" class="read-more-btn">
                      <span>Read more</span>
                      <img src="{{ asset('frontend/assets/images/icons/right-arrow-bold.svg')}}" alt="">
                      </a>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="clearfix"></div>

        <section class="proofs-section" id="proofs">
          <div class="proofs-content">
            <h2>{{ $proofs?->heading }}</h2>
          </div>

          @php
            $proofCardClass = ['card-1', 'card-3', 'card-2', 'card-4'];
            $proofMetricsClass = [
              'metrics-card metrics-card-large metrics-card-large-one',
              'metrics-card orange-bg metrics-card-large-three',
              'metrics-card metrics-card-large-two',
              'metrics-card metrics-card-large-four',
            ];
          @endphp
          @foreach ($proofs?->items ?? [] as $i => $item)
          <div class="proof-card {{ $proofCardClass[$i % 4] }}">
            <div class="{{ $proofMetricsClass[$i % 4] }}" @if (!empty($item['image'])) style="background-image:url('{{ asset('home/proofs/' . $item['image']) }}');" @endif>
              <div class="metrics-content">
                @if (!empty($item['icon_svg']))
                <span class="metrics-icon">{!! $item['icon_svg'] !!}</span>
                @elseif (!empty($item['icon']))
                <img src="{{ asset('home/proofs/' . $item['icon']) }}" class="metrics-icon">
                @endif
                <h3>{{ $item['text'] ?? '' }}</h3>
              </div>
            </div>
          </div>
          @endforeach
        </section>

          @php $testimonialItems = optional($testimonial)->items ?? []; @endphp
          @if(count($testimonialItems))
          <section class="employee-testimonials-custom-sec">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($testimonial)->heading ?: 'Testimonials' }}</h2>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="owl-carousel employee-testimonials-carousel">

                @foreach($testimonialItems as $item)
                <!-- Testimonial {{ $loop->iteration }} -->
                <div class="employee-testimonial-card">
                  <span class="testimonial-shape"></span>
                  <div class="testimonial-icon">
                    <i class="fa fa-quote-left"></i>
                  </div>
                  <p class="testimonial-text">{!! nl2br(e($item['text'] ?? '')) !!}</p>
                  <div class="testimonial-footer">
                    <div class="testimonial-info">
                      <h4>{{ $item['name'] ?? '' }}</h4>
                      <span>{{ $item['designation'] ?? '' }}@if(!empty($item['designation']) && !empty($item['company'])), @endif @if(!empty($item['company']))<br>{{ $item['company'] }}@endif</span>
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

        <section class="cta-section">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="cta-text">
                 <div class="heading">
                    <h2 data-aos="fade-up" data-aos-duration="1000">{{ $cta?->heading }}</h2>
                    {!! $cta?->description !!}
                  </div>
                  @if ($cta?->button_text)
                  <a class="btn-default btn-black" href="{{ $cta?->button_link ?: '#' }}">{{ $cta->button_text }}</a>
                  @endif              
                
                </div>
            </div>
          </div>
        </div>
      </section>

        
        @include('components.frontend.footer')
      </div>
    </div>
    <!-- Leadership Modals -->
    @foreach ($leadership?->leaders ?? [] as $i => $leader)
    <div class="modal fade" id="team{{ $i }}" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{ $leader['name'] ?? '' }}</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              {!! $leader['description'] ?? '' !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
     @include('components.frontend.main-js')

  </body>
</html>