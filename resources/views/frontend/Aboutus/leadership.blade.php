
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
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('about-us/leadership/banner/' . $banner->background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ optional($banner)->title ?? 'Leadership' }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_parent ?? 'About' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->title ?? 'Leadership' }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <section class="catalyst-about-leadership-custom-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="about-text">
                <div class="heading heading-center">
                  <h6>{{ optional($content)->intro_sub_heading ?? 'About' }}</h6>
                  <h2>{{ optional($content)->intro_heading ?? 'Leadership' }}</h2>
                </div>
                @if(optional($content)->intro_description)
                <p class="text-center">{{ $content->intro_description }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="catalyst-board-of-director-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1400">
                <h2>{{ optional($content)->board_heading ?? 'Board of Directors' }}</h2>
              </div>
            </div>
          </div>
          <div class="catalyst-bod-main-sec">
            @if(optional($content)->board_members && count($content->board_members))
              @foreach($content->board_members as $index => $member)
                @php $imgLeft = ($index % 2 === 0); @endphp
                <div class="cata-bod-main-card-sec">
                  <div class="row">
                    @if($imgLeft)
                    <div class="col-md-3">
                      <div class="cata-bod-main-card-img-sec">
                        <img src="{{ !empty($member['image']) ? asset('about-us/leadership/content/' . $member['image']) : asset('frontend/assets/images/team/placeholder.webp') }}" alt="{{ $member['name'] ?? '' }}">
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="cata-bod-main-card-content-sec">
                        <h3>{{ $member['name'] ?? '' }}</h3>
                        <h5>{{ $member['designation'] ?? '' }}</h5>
                        {!! nl2br(e($member['description'] ?? '')) !!}
                      </div>
                    </div>
                    @else
                    <div class="col-md-9">
                      <div class="cata-bod-main-card-content-sec">
                        <h3>{{ $member['name'] ?? '' }}</h3>
                        <h5>{{ $member['designation'] ?? '' }}</h5>
                        {!! nl2br(e($member['description'] ?? '')) !!}
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="cata-bod-main-card-img-sec">
                        <img src="{{ !empty($member['image']) ? asset('about-us/leadership/content/' . $member['image']) : asset('frontend/assets/images/team/placeholder.webp') }}" alt="{{ $member['name'] ?? '' }}">
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </section>
      <section class="cata-leadership-sec">
        <div class="container">
          <div class="heading heading-center">
            <h2>{{ optional($content)->team_heading ?? 'Leadership Team' }}</h2>
          </div>
          <div class="cata-leadership-main-card-sec">
            <div class="row">
              @if(optional($content)->team_members && count($content->team_members))
                @foreach($content->team_members as $member)
                  <div class="col-md-12">
                    <div class="leader-card">
                      <div class="leader-image">
                        <img src="{{ !empty($member['image']) ? asset('about-us/leadership/content/' . $member['image']) : asset('frontend/assets/images/team/placeholder.webp') }}" alt="{{ $member['name'] ?? '' }}">
                      </div>
                      <div class="leader-content">
                        <h3>{{ $member['name'] ?? '' }}</h3>
                        <h5 class="leader-designation">{{ $member['designation'] ?? '' }}</h5>
                        {!! nl2br(e($member['description'] ?? '')) !!}
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
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