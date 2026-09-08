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
        <div class="breadcrumb-header-bg" @if($banner) style="background-image: url('{{ asset($banner) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>Our Services</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>Services</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="services-index-sec">
        <div class="container">

          <div class="row">
            <div class="col-sm-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>What We Do</h2>
                <p class="services-index-lead">
                  Catalyst Trusteeship Limited acts as trustee, agent and custodian across regulated
                  and unregulated markets in India and at GIFT City. Choose a service below to read
                  what it covers and how we deliver it.
                </p>
              </div>
            </div>
          </div>

          @foreach($groups as $group)
          @php $category = $group['category']; @endphp
          <div class="services-index-group" data-aos="fade-up" data-aos-duration="1000">

            <div class="services-index-group-head">
              @if($category->icon)
              <span class="services-index-group-icon">
                <img src="{{ asset('service-uploads/categories/'.$category->icon) }}" alt="">
              </span>
              @endif
              <h3>{{ $category->name }}</h3>
              <span class="services-index-count">{{ $group['services']->count() }} services</span>
            </div>

            <div class="row services-index-row">
              @foreach($group['services'] as $service)
              @php $url = $service->url; @endphp
              <div class="col-sm-3 col-xs-6">
                {{-- A service with no page yet is still listed, but is not
                     clickable - a link that goes nowhere is worse than none. --}}
                <{{ $url ? 'a' : 'div' }} class="services-index-card" @if($url) href="{{ $url }}" @endif>
                  <span class="services-index-card-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                  <h4 class="services-index-card-title">{{ $service->name }}</h4>
                  @if($url)
                  <span class="services-index-card-cta">Read More <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                  @else
                  <span class="services-index-card-soon">Coming soon</span>
                  @endif
                </{{ $url ? 'a' : 'div' }}>
              </div>
              @endforeach
            </div>

          </div>
          @endforeach

        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
