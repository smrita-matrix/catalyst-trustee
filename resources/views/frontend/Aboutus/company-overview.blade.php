
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
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('about-us/company-overview/banner/' . $banner->background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ optional($banner)->title ?? 'Company Overview' }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_parent ?? 'About' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->title ?? 'Company Overview' }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <section class="catalyst-vision-mission-sec">
        <div class="container">
          <div class="row">
            <!-- Left Image Section -->
            <div class="col-md-6">
              <div class="cvm-image-wrapper">
                <div class="catalyst-v-m-img-sec">
                  <img src="{{ optional($introduction)->image ? asset('about-us/company-overview/introduction/' . $introduction->image) : asset('images/about/company-overview-img.webp') }}" alt="About" class="img-responsive cvm-main-img">
                  <div class="happy-customer-box">
                    <div class="happy-customer-box-content">
                      <div class="satisfy-client-content">
                        <h2>{{ optional($introduction)->experience_number ?? '29+' }}</h2>
                        <p>{{ optional($introduction)->experience_label ?? 'Years Of Fiduciary & Trusteeship Expertise' }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="about-experience-box">
                    <div class="about-experience-box-content">
                      <p>{{ optional($introduction)->established_label ?? 'Established In' }}</p>
                      <h2><span class="counter">{{ optional($introduction)->established_year ?? '1997' }}</span></h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Text Section -->
            <div class="col-md-6">
              <div class="about-text">
                <div class="heading">
                  <h6>{{ optional($introduction)->sub_heading ?? 'Company Overview' }}</h6>
                  <h2>{{ optional($introduction)->heading ?? 'Introduction to Catalyst Trusteeship Limited' }}</h2>
                </div>
                @if(optional($introduction)->tagline)
                <p>{{ $introduction->tagline }}</p>
                @endif

                @php
                    $descHtml   = trim(optional($introduction)->description ?? '');
                    $moreExtra  = trim(optional($introduction)->more_content ?? '');
                    $visibleN   = 3; // paragraphs shown before "Read More"
                    $paras = [];
                    if ($descHtml !== '') {
                        preg_match_all('/<p\b[^>]*>.*?<\/p>/is', $descHtml, $m);
                        $paras = $m[0] ?? [];
                    }
                    // The same opening line is easy to type into the tagline box
                    // and again at the top of the description, which prints it
                    // twice. Keep the tagline and drop the repeat.
                    $plain = fn ($html) => mb_strtolower(trim(preg_replace('/\s+/', ' ',
                        html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8'))));

                    $taglineText = $plain(optional($introduction)->tagline);

                    if ($taglineText !== '' && $paras) {
                        $kept = array_values(array_filter($paras, fn ($p) => $plain($p) !== $taglineText));

                        if (count($kept) !== count($paras)) {
                            $paras    = $kept;
                            $descHtml = implode('', $paras);
                        }
                    }

                    $useSplit    = count($paras) > $visibleN;
                    $visibleHtml = $useSplit ? implode('', array_slice($paras, 0, $visibleN)) : $descHtml;
                    $hiddenHtml  = $useSplit ? implode('', array_slice($paras, $visibleN)) : '';
                    $hiddenCombined = trim($hiddenHtml . $moreExtra);
                @endphp

                {!! $visibleHtml !!}

                @if($hiddenCombined !== '')
                  <div class="more-content">
                    {!! $hiddenCombined !!}
                  </div>

                  <a class="btn-default company-overview-btn" id="companyOverviewReadMore" href="javascript:void(0)" data-more="{{ optional($introduction)->button_text ?? 'Read More' }}" data-less="Read Less">{{ optional($introduction)->button_text ?? 'Read More' }}</a>
                @endif
              </div>
            </div>

          </div>
        </div>
      </section>
      <section class="catalyst-company-overview-sec">
        <div class="container">
          <div class="row vm-cards">

            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($visionMission)->heading ?? 'Vision & Mission' }}</h2>
              </div>
            </div>

            @if(optional($visionMission)->items && count($visionMission->items))
              @foreach($visionMission->items as $item)
                @php
                  $vmTag = strtoupper(trim($item['tag'] ?? ''));
                  $vmClass = \Illuminate\Support\Str::contains(strtolower($vmTag), 'mission') ? 'mission' : 'vision';
                @endphp
                <div class="col-md-6 col-sm-6">
                  <div class="vm-card {{ $vmClass }}">
                    <span class="tag">{{ $vmTag }}</span>
                    <div class="icon-ring">
                      @if(!empty($item['icon']))
                        <img src="{{ asset('about-us/company-overview/vision-mission/' . $item['icon']) }}" alt="">
                      @endif
                    </div>
                    <h3>{{ $item['title'] ?? '' }}</h3>
                    <p>{{ $item['description'] ?? '' }}</p>
                    <div class="corner-glow"></div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </section>
       @include('components.frontend.footer')
    </div>
  </div>
       @include('components.frontend.main-js')

<script>
    (function () {
        var btn = document.getElementById('companyOverviewReadMore');
        if (!btn) return;
        var moreContent = document.querySelector('.about-text .more-content');
        if (!moreContent) return;

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var isOpen = moreContent.style.display === 'block';
            if (isOpen) {
                moreContent.style.display = 'none';
                btn.textContent = btn.getAttribute('data-more');
            } else {
                moreContent.style.display = 'block';
                btn.textContent = btn.getAttribute('data-less');
            }
        });
    })();
</script>
</body>
</html>