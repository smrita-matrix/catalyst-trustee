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
                  <img src="images/home/why-img2.webp" class="img-responsive">
                </div>
              </div> -->
            </div>
          </div>
        </section>

        <section class="all-services-wrap">
         
          <section class="sebi-services-wrap">
            <div class="container-fluid">
              <!-- <div class="heading heading-center heading-white">
                <h2>SEBI-Regulated Trustee Services </h2>
              </div> -->

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
                                <img src="images/icons/right-arrow-bold.svg">
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
                        <a class="text-btn" href="{{ ($item['read_more_link'] ?? '') ?: '#' }}"><span class="btn-text"><span>Learn More <img src="images/icons/right-arrow-bold-white.svg"></span></span></a>
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
                      <img src="images/icons/right-arrow.png">
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
                          <a data-toggle="modal" data-target="#team{{ $i }}"><img src="images/icons/right-arrow-bold.svg"/></a>
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
                      <img src="images/icons/right-arrow-bold.svg" alt="">
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
        <section class="cta-section" @if ($cta?->background_image) style="background-image:url('{{ asset('home/cta/' . $cta->background_image) }}');" @endif>
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

    <!-- 1. Adani Airport -->
    <div class="modal fade" id="adaniairport" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Adani Airport</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>successfully acted as the Facility Agent for a landmark $750 Million External Commercial Borrowing (ECB) facility for Adani Airport Holdings Limited, concluded on 30 May 2025. This transaction underscores our expertise in structuring and managing complex cross-border financing arrangements.</p>
              <p>As the central coordinating entity between the borrower and multiple international lenders, we ensured seamless execution, robust regulatory compliance, and transparent communication across stakeholders. This milestone further reinforces our position as a trusted partner in enabling large-scale infrastructure financing on a global platform.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. GMR Airports Ltd -->
    <div class="modal fade" id="gmrairports" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">GMR Airports Ltd</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>acted as the Debenture Trustee for the issuance of ₹5,900 Crore Listed, Rated, Secured, Redeemable Non-Convertible Debentures (NCDs) by GMR Airports Limited. This transaction reinforces our strong presence in the infrastructure sector, where scale, precision, and trust are paramount. Our role ensured robust monitoring, regulatory adherence, and stakeholder alignment, further strengthening our position as a reliable partner in large-scale infrastructure financing.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. HDFC ABS Deal -->
    <div class="modal fade" id="hdfcabsdeal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">India’s Largest ABS Securitisation Deal Concluded by HDFC Bank</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>HDFC Bank Limited has successfully concluded India’s largest Asset-Backed Securitization (ABS) deal, securitizing a pool of new car loans worth ₹12,372 crore. The landmark transaction, finalized on 27th November 2024, is backed by a provisional AAA rating and structured as a Listed Pass-Through Certificate (PTC) transaction. Catalyst Trusteeship Limited acted as the trustee for this recordbreaking deal, showcasing the strength and innovation within India's financial markets. This monumental transaction not only sets a new benchmark for the securitization market but also reinforces investor confidence in high-quality retail loan-backed securities.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4. Mankind Pharma -->
    <div class="modal fade" id="mankindpharma" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Mankind Pharma Limited</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>Mankind Pharma Limited completed a significant Rs. 13,768 Crore acquisition of Bharat Serums and Vaccines Limited, funded through internal resources and a debt issuance of Rs. 5000 Crores. The debt was raised via Listed, Secured, NonConvertible Debentures (NCDs) in three series, with Catalyst Trusteeship Limited proudly acting as the Debenture Trustee for this transaction. This landmark deal was executed in September.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 5. Adani Enterprises -->
    <div class="modal fade" id="adanienterprises" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Adani Enterprises</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>₹7,500 crore by Adani Enterprises. These transactions highlight our expertise in managing complex debt structures with strong covenant monitoring, timely security creation, and robust regulatory compliance.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 6. Union Bank -->
    <div class="modal fade" id="unionbank" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Union Bank</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>We are pleased to have acted as Debenture Trustee in the successful issuance by UnionBank of India, for the ₹7,500 crore listed, secured debenture issuance, with an allotment of ₹3,000 crore completed on March 24, 2026. This transaction underscores continued investor confidence in large public sector institutions and reflects our strong capabilities in managing high-value, complex mandates.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 7. JSW Steel -->
    <div class="modal fade" id="jswsteel" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">JSW Steel</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>are proud to have acted as the Debenture Trustee for the issuance of ₹7,000 Crore Listed, Rated, Secured, Redeemable Non-Convertible Debentures (NCDs) by JTPM Metal Traders Private Limited.</p>
              <p>This landmark mandate stands among our largest transactions, highlighting our capability to manage high-value and complex debt issuances within the metals and commodities sector. Through rigorous oversight and structured governance, we ensured investor protection and seamless execution across the entire transaction lifecycle.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 8. Bajaj Finance -->
    <div class="modal fade" id="bajajfinance" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Bajaj Finance Limited</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>Debenture Trustee for ₹5,000 Cr NCD Issuance. We are pleased to share that we acted as the debenture trustee for the issuance of Listed, Rated, Secured, Redeemable Non-Convertible Debentures (NCDs) aggregating to ₹5,000 crore by Bajaj Finance Limited. This transaction underscores our continued role in strengthening India’s financial markets through diligent and trusted trusteeship services.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 9. Poonawalla Fincorp -->
    <div class="modal fade" id="poonawalafincorp" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Poonawala Fincorp</h4>
          </div>
          <div class="modal-body">
            <div class="team-text-box">
              <p>successfully acted as the Debenture Trustee for the issuance of ₹5,000 Crore NCDs, raised in two tranches of ₹2,000 Crore and ₹3,000 Crore by Poonawalla Fincorp Limited.</p>
              <p>This transaction reflects our continued leadership in the NBFC space, supporting prominent financial institutions in strengthening their capital base. Through vigilant monitoring of financial covenants, security assets, and compliance frameworks, we ensured comprehensive investor protection across multiple issuances.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

     @include('components.frontend.main-js')

  </body>
</html>