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
      <section class="contact-us-contact-information-custom-sec">
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

      {{-- ===== Enquiry Form ===== --}}
      <section class="contact-us-enquiry-form-custom-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($content)->enquiry_heading ?: 'Enquiry Form' }}</h2>
              </div>
            </div>
          </div>
          <div class="contact-us-enquiry-form-two-col-sec">
            <div class="row">
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="contact-form-image-col">
                  <img src="{{ optional($content)->form_image ? asset('contact-media/form/'.$content->form_image) : asset('frontend/assets/images/home/contact-us-img.webp') }}" alt="">
                </div>
              </div>
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="contact-form-fields-col">
                  <h2>{{ optional($content)->form_heading ?: 'Get in Touch' }}</h2>
                  <form>
                    <div class="cf-form-row">
                      <div class="cf-field">
                        <label for="firstName">First Name<span class="required">*</span></label>
                        <input type="text" id="firstName" name="firstName" placeholder="Name" required>
                      </div>
                      <div class="cf-field">
                        <label for="lastName">Last Name<span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
                      </div>
                    </div>
                    <div class="cf-form-row">
                      <div class="cf-field">
                        <label for="mobile">Mobile Number<span class="required">*</span></label>
                        <input type="tel" id="mobile" name="mobile" required>
                      </div>
                      <div class="cf-field">
                        <label for="email">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                      </div>
                    </div>
                    <div class="cf-form-row">
                      <div class="cf-field">
                        <label for="services">Services<span class="required">*</span></label>
                        <select id="services" name="services" required>
                          <option value="" selected disabled>Select Services</option>
                          @foreach($content ? $content->optionList('services_options') : [] as $opt)
                          <option value="{{ $opt }}">{{ $opt }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="cf-field">
                        <label for="location">Select Location</label>
                        <select id="location" name="location">
                          <option value="" selected disabled>Select Location</option>
                          @foreach($content ? $content->optionList('location_options') : [] as $opt)
                          <option value="{{ $opt }}">{{ $opt }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="cf-field">
                      <label for="comments">Comments / Questions<span class="required">*</span></label>
                      <textarea id="comments" name="comments" placeholder="Comments / Questions" required></textarea>
                    </div>
                    <button type="submit" class="btn-default">Submit</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {{-- ===== Office Locations ===== --}}
      @if($mainOffices->count() || $branchOffices->count())
      <section class="contact-us-office-locations-custom-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($content)->office_heading ?: 'Office Locations' }}</h2>
              </div>
            </div>
          </div>

          @if($mainOffices->count())
          <div class="office-group office-group--hq">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="office-subhead" data-aos="fade-up" data-aos-duration="800">
                  <h3 class="office-subtitle">{{ optional($content)->main_office_subtitle ?: 'Main Branch Office' }}</h3>
                </div>
              </div>
            </div>
            <div class="row">
              @foreach($mainOffices as $o)
              <div class="col-sm-6" data-aos="fade-up" data-aos-duration="900">
                <div class="hq-card">
                  <h3 class="hq-city">{{ $o->city }}</h3>
                  @if($o->role)<p class="hq-role">{{ $o->role }}</p>@endif
                  @if($o->address)<p class="hq-address">{!! nl2br(e($o->address)) !!}</p>@endif
                  @if($o->contact || $o->email)
                  <p class="hq-contact">
                    @if($o->contact){{ $o->contact }}<br>@endif
                    @if($o->email)<a href="mailto:{{ $o->email }}">{{ $o->email }}</a>@endif
                  </p>
                  @endif
                  @if($o->map_link)
                  <a href="{{ $o->map_link }}" target="_blank" class="hq-link">View location
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 8h10M9 4l4 4-4 4" /></svg>
                  </a>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          @if($branchOffices->count())
          <div class="office-group office-group--network">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="office-subhead" data-aos="fade-up" data-aos-duration="800">
                  <h3 class="office-subtitle office-subtitle--dark">{{ optional($content)->other_office_subtitle ?: 'Other Branch Office' }}</h3>
                </div>
              </div>
            </div>
            <div class="row">
              @foreach($branchOffices as $o)
              <div class="col-sm-6 col-md-3" data-aos="fade-up" data-aos-duration="800">
                <div class="branch-card branch-card--pan">
                  @if($o->tag)<span class="branch-tag">{{ $o->tag }}</span>@endif
                  <h4 class="branch-city">{{ $o->city }}</h4>
                  @if($o->address)<p class="branch-address">{!! nl2br(e($o->address)) !!}</p>@endif
                  @if($o->email)<a class="branch-email" href="mailto:{{ $o->email }}">{{ $o->email }}</a>@endif
                </div>
              </div>
              @endforeach
            </div>

            @if(trim((string) optional($content)->notice_text) !== '')
            <div class="row">
              <div class="col-sm-12" data-aos="fade-up" data-aos-duration="800">
                <div class="notice-bar"><p>{!! $content->notice_text !!}</p></div>
              </div>
            </div>
            @endif
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
