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

  @php
    $icons = 'frontend/assets/images/icons/';
    $bTitle = optional($content)->banner_title ?: 'Contact';
  @endphp

  <div id="smooth-wrapper">
    <div id="smooth-content">

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($content)->banner_background_image) style="background-image: url('{{ asset('contact-media/banner/'.$content->banner_background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $bTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($content)->banner_breadcrumb_parent ?: 'Contact' }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {{-- ===== Contact Information ===== --}}
      @if(optional($content)->phone || optional($content)->email || optional($content)->address)
      <section class="contact-us-contact-information-custom-sec" id="contact-information" data-section-tab>
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($content)->info_heading ?: 'Contact Information' }}</h2>
              </div>
            </div>
          </div>
          <div class="contact-us-con-info-cus-card-main-sec">
            <div class="row">
              @if(optional($content)->phone)
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="con-us-con-info-cus-box" data-aos="fade-up" data-aos-duration="800">
                  <div class="con-us-con-info-cus-content">
                    <div class="con-us-con-info-cus-icon"><img src="{{ asset($icons.'phone-icon.svg') }}"></div>
                    <h4>Phone</h4>
                    <p><a href="{{ $content->phone_link ?: 'tel:'.$content->phone }}">{{ $content->phone }}</a></p>
                  </div>
                </div>
              </div>
              @endif
              @if(optional($content)->email)
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="con-us-con-info-cus-box" data-aos="fade-up" data-aos-duration="800">
                  <div class="con-us-con-info-cus-content">
                    <div class="con-us-con-info-cus-icon"><img src="{{ asset($icons.'mail-icon.svg') }}"></div>
                    <h4>Email</h4>
                    <p><a href="{{ $content->email_link ?: 'mailto:'.$content->email }}">{{ $content->email }}</a></p>
                  </div>
                </div>
              </div>
              @endif
              @if(optional($content)->address)
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="con-us-con-info-cus-box" data-aos="fade-up" data-aos-duration="800">
                  <div class="con-us-con-info-cus-content">
                    <div class="con-us-con-info-cus-icon"><img src="{{ asset($icons.'address-icon.svg') }}"></div>
                    <h4>Address</h4>
                    <p><a href="{{ $content->address_link ?: '#' }}" target="_blank">{{ $content->address }}</a></p>
                  </div>
                </div>
              </div>
              @endif
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
