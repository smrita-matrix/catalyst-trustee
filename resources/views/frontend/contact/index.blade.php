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

      {{-- ===== Enquiry Form ===== --}}
      <section class="contact-us-enquiry-form-custom-sec" id="enquiry-form" data-section-tab>
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
                  <form action="{{ route('frontend.contact.store') }}" method="POST" id="enquiry-form" novalidate>
                    @csrf

                    @if(session('message'))
                      <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    @if($errors->any())
                      <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                      </div>
                    @endif

                    <div class="cf-form-row">
                      <div class="cf-field">
                        <label for="firstName">First Name<span class="required">*</span></label>
                        <input type="text" id="firstName" name="first_name" value="{{ old('first_name') }}" placeholder="Name">
                      </div>
                      <div class="cf-field">
                        <label for="lastName">Last Name<span class="required">*</span></label>
                        <input type="text" id="lastName" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                      </div>
                    </div>
                    <div class="cf-form-row">
                      <div class="cf-field">
                        <label for="mobile">Mobile Number<span class="required">*</span></label>
                        <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}">
                      </div>
                      <div class="cf-field">
                        <label for="email">Email<span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                      </div>
                    </div>
                    <div class="cf-form-row">
                      <div class="cf-field">
                        <label for="services">Services<span class="required">*</span></label>
                        <select id="services" name="service">
                          <option value="" selected disabled>Select Services</option>
                          @foreach($content ? $content->optionList('services_options') : [] as $opt)
                          <option value="{{ $opt }}" {{ old('service') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="cf-field">
                        <label for="location">Select Location</label>
                        <select id="location" name="location">
                          <option value="" selected disabled>Select Location</option>
                          @foreach($content ? $content->optionList('location_options') : [] as $opt)
                          <option value="{{ $opt }}" {{ old('location') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="cf-field">
                      <label for="comments">Comments / Questions<span class="required">*</span></label>
                      <textarea id="comments" name="comments" placeholder="Comments / Questions">{{ old('comments') }}</textarea>
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
      <section class="contact-us-office-locations-custom-sec" id="office-locations" data-section-tab>
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

  <style>
    .field-error { display:block; margin-top:6px; color:#d9534f; font-size:13px; }
    #enquiry-form .is-invalid { border-color:#d9534f !important; }
  </style>

  <script>
    // Client-side checks so mistakes are caught before the page reloads.
    // Every rule here is enforced again on the server.
    (function () {
      var form = document.getElementById('enquiry-form');
      if (!form) { return; }

      var LETTERS = /^[A-Za-z\u00C0-\u024F\s.'-]+$/;
      var EMAIL   = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
      var PHONE   = /^[0-9+\s-]{7,20}$/;

      function problem(field) {
        var name  = field.getAttribute('name');
        var value = (field.value || '').trim();

        if (name === 'first_name' || name === 'last_name') {
          if (!value) { return 'This field is required.'; }
          if (!LETTERS.test(value)) { return 'Letters only - no numbers or symbols.'; }
          return null;
        }
        if (name === 'email') {
          if (!value) { return 'Please enter your email address.'; }
          if (!EMAIL.test(value)) { return 'Please enter a valid email address.'; }
          return null;
        }
        if (name === 'mobile') {
          if (!value) { return 'Please enter your mobile number.'; }
          if (!PHONE.test(value)) { return 'Digits only, at least 7 of them.'; }
          return null;
        }
        if (name === 'service') {
          if (!value) { return 'Please choose a service.'; }
          return null;
        }
        if (name === 'comments') {
          if (!value) { return 'Please enter your comments or questions.'; }
          if (value.length > 2000) { return 'Please keep it within 2000 characters.'; }
          return null;
        }
        return null;
      }

      function showError(field, message) {
        clearError(field);
        field.classList.add('is-invalid');
        var span = document.createElement('span');
        span.className = 'field-error';
        span.textContent = message;
        field.parentNode.appendChild(span);
      }

      function clearError(field) {
        field.classList.remove('is-invalid');
        var existing = field.parentNode.querySelector('.field-error');
        if (existing) { existing.parentNode.removeChild(existing); }
      }

      var fields = ['first_name', 'last_name', 'mobile', 'email', 'service', 'comments']
        .map(function (n) { return form.querySelector('[name="' + n + '"]'); })
        .filter(function (el) { return !!el; });

      fields.forEach(function (field) {
        ['blur', 'change'].forEach(function (evt) {
          field.addEventListener(evt, function () {
            var message = problem(field);
            if (message) { showError(field, message); } else { clearError(field); }
          });
        });
      });

      /**
       * Bring the first field that needs fixing into view.
       *
       * The message now sits under the field rather than in a list at the top,
       * so the page has to move to it — otherwise a problem below the fold
       * would look like nothing happened. The site runs GSAP ScrollSmoother,
       * which hijacks the scroll position, so hand the job to it when it is
       * there and fall back to normal scrolling when it is not.
       */
      function reveal(field) {
        var smooth = (window.ScrollSmoother && typeof window.ScrollSmoother.get === 'function')
          ? window.ScrollSmoother.get()
          : null;

        if (smooth && typeof smooth.scrollTo === 'function') {
          smooth.scrollTo(field, true, 'center center');
        } else if (typeof field.scrollIntoView === 'function') {
          field.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        field.focus({ preventScroll: true });
      }

      form.addEventListener('submit', function (e) {
        var failed = [];

        fields.forEach(function (field) {
          var message = problem(field);
          if (message) {
            showError(field, message);
            failed.push(field);
          } else {
            clearError(field);
          }
        });

        if (failed.length) {
          e.preventDefault();
          reveal(failed[0]);
          return;
        }

        var button = form.querySelector('button[type="submit"]');
        if (button) {
          button.disabled = true;
          button.textContent = 'Submitting...';
        }
      });
    })();
  </script>

</body>

</html>
