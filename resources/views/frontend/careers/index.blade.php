<!DOCTYPE html>
<html lang="en">

<head>
  @include('components.frontend.head')
  <style>
    /* Inline error text for the client-side checks. */
    .field-error {
      display: block;
      margin-top: 6px;
      color: #d9534f;
      font-size: 13px;
    }
    .career-form .form-control.is-invalid {
      border-color: #d9534f;
    }
  </style>
</head>

<body>
  <div class="body-overlay"></div>
  <header>
    @include('components.frontend.header')
  </header>

  <div id="smooth-wrapper">
    <div id="smooth-content">

      @php $bTitle = optional($content)->banner_title ?: 'Careers'; @endphp

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($content)->banner_image) style="background-image: url('{{ asset('career-uploads/banner/'.$content->banner_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $bTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($content)->breadcrumb_child ?: $bTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="careers-custom-sec" id="life-at-catalyst" data-section-tab>
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                @if(optional($content)->intro_heading)<h2>{{ $content->intro_heading }}</h2>@endif
                @if(optional($content)->intro_text)<p>{{ $content->intro_text }}</p>@endif
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- Life at Catalyst stories.

           One band per story, following the approved design: the photo slider
           and the write-up side by side, the sides swapping each time and the
           background alternating between the pink tint and white. Stories come
           from the dashboard, so any number of them keeps that rhythm. --}}
      @php $stories = optional($content)->life_stories ?: []; @endphp
      @foreach($stories as $i => $story)
        @php
          $title  = trim($story['title'] ?? '');
          $text   = trim($story['text'] ?? '');
          $photos = array_values(array_filter($story['images'] ?? []));
          $tinted = $i % 2 === 0;      // first band tinted, then alternating
          $flip   = $i % 2 === 1;      // text first on every second band
        @endphp
        @continue($title === '' && $text === '' && !$photos)

        <section class="jour-built-trust-sin-sec {{ $tinted ? 'is-tinted' : '' }} {{ $flip ? 'is-reversed' : '' }}">
          <div class="container">
            <div class="row">

              @if($photos)
              <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="inc-day-slider-sec">
                  <div class="inc-day-slider owl-carousel owl-theme">
                    @foreach($photos as $photo)
                    <div class="item">
                      <div class="inc-day-slide">
                        <img src="{{ asset('career-uploads/life/'.$photo) }}"
                             alt="{{ $title }}" loading="lazy">
                      </div>
                    </div>
                    @endforeach
                  </div>
                  <div class="inc-day-slider__foot">
                    <div class="inc-day-slider__count">
                      <span data-current>01</span><i>/</i><span data-total>{{ str_pad(count($photos), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="inc-day-slider__progress"><span data-progress></span></div>
                  </div>
                </div>
              </div>
              @endif

              <div class="{{ $photos ? 'col-md-6' : 'col-md-12' }} col-sm-12 col-xs-12">
                <div class="life-stories">
                  <div class="life-story" data-aos="fade-up" data-aos-duration="900">
                    @if($title !== '')<h3>{{ $title }}</h3>@endif
                    @foreach(preg_split('/\R\s*\R/', $text) as $para)
                      @php $para = trim($para); @endphp
                      @if($para !== '')<p>{{ $para }}</p>@endif
                    @endforeach
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>
      @endforeach

      <section class="careers-custom-sec" id="current-openings" data-section-tab>
        <div class="container">
          @if($openings->count())
          <div class="opening-position-wrap">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">

                @foreach($openings as $opening)
                <div class="single-opening">

                  <div class="opening-header">
                    <h3>{{ $opening->title }}</h3>
                    <div class="opening-action">
                      <a href="#submit-resume" class="btn-default apply-now" data-position="{{ $opening->title }}">
                        Apply Now
                      </a>
                    </div>
                  </div>

                  <div class="opening-meta">
                    @if($opening->experience)
                    <div class="opening-meta-item">
                      <img src="{{ asset('frontend/assets/images/icons/briefcase.svg') }}" alt="Experience">
                      <div>
                        <span>Experience : </span>
                        <strong>{{ $opening->experience }}</strong>
                      </div>
                    </div>
                    @endif

                    @if($opening->vacancies)
                    <div class="opening-meta-item">
                      <img src="{{ asset('frontend/assets/images/icons/chair.svg') }}" alt="Vacancies">
                      <div>
                        <span>Vacancies : </span>
                        <strong>{{ $opening->vacancies }}</strong>
                      </div>
                    </div>
                    @endif

                    @if($opening->qualification)
                    <div class="opening-meta-item">
                      <img src="{{ asset('frontend/assets/images/icons/knowledge.svg') }}" alt="Qualification">
                      <div>
                        <span>Qualification : </span>
                        <strong>{{ $opening->qualification }}</strong>
                      </div>
                    </div>
                    @endif

                    @if($opening->location)
                    <div class="opening-meta-item">
                      <img src="{{ asset('frontend/assets/images/icons/address-icon.svg') }}" alt="Location">
                      <div>
                        <span>Location : </span>
                        <strong>{{ $opening->location }}</strong>
                      </div>
                    </div>
                    @endif
                  </div>

                  @if($opening->description)
                  <div class="opening-description">
                    <p>{!! nl2br(e($opening->description)) !!}</p>
                  </div>
                  @endif

                </div>
                @endforeach

              </div>
            </div>
          </div>
          @else
          <div class="row">
            <div class="col-md-12">
              <p class="text-center">There are no openings listed at the moment. You are welcome to send us your resume.</p>
            </div>
          </div>
          @endif
        </div>
      </section>

      <section class="career-form-sec" id="submit-resume" data-section-tab>
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h6>{{ optional($content)->form_sub_heading ?: 'Apply Now' }}</h6>
                <h2>{{ optional($content)->form_heading ?: 'Submit Your Resume' }}</h2>
              </div>
            </div>
            <div class="col-md-12 col-sm-12">
              <div class="career-form">

                @if($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                  </div>
                @endif

                <form action="{{ route('frontend.careers.store') }}" method="POST" enctype="multipart/form-data" id="career-form" novalidate>
                  @csrf

                  {{-- Filled in by the client-side checks when a submit is blocked. --}}
                  <div class="alert alert-danger" id="career-form-errors" style="display:none;">
                    <strong>Please correct the following:</strong>
                    <ul class="mb-0"></ul>
                  </div>

                  <div class="form-group col-md-4">
                    <label>First Name <span style="color:red;">*</span></label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                  </div>
                  <div class="form-group col-md-4">
                    <label>Last Name <span style="color:red;">*</span></label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                  </div>
                  <div class="form-group col-md-4">
                    <label>Email <span style="color:red;">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                  </div>
                  <div class="form-group col-md-4">
                    <label>Phone Number <span style="color:red;">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                  </div>
                  <div class="form-group col-md-4">
                    <label>City <span style="color:red;">*</span></label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                  </div>
                  <div class="form-group col-md-4">
                    <label>Position Applying For <span style="color:red;">*</span></label>
                    <select name="position" class="form-control" id="position-select">
                      <option value="">— Select —</option>
                      @foreach($openings as $opening)
                        <option value="{{ $opening->title }}" {{ old('position') === $opening->title ? 'selected' : '' }}>{{ $opening->title }}</option>
                      @endforeach
                      <option value="Other" {{ old('position') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Your Intro? &amp; Why should we hire you?</label>
                    <textarea class="form-control" name="intro">{{ old('intro') }}</textarea>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Attach your resume <span style="color:red;">*</span></label>
                    <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                    <small class="text-muted">PDF, DOC or DOCX — maximum 5MB.</small>
                  </div>
                  <div class="form-group text-center col-md-12">
                    <button type="submit" name="submit" class="btn-default">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')

  <script>
    // Client-side checks so mistakes are caught before the page reloads.
    // Every rule here is enforced again on the server.
    (function () {
      var form = document.getElementById('career-form');
      if (!form) { return; }

      // \u00C0-\u024F covers accented Latin letters; written as escapes so the
      // rule never depends on the file's character encoding.
      var LETTERS  = /^[A-Za-z\u00C0-\u024F\s.'-]+$/;
      var EMAIL    = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
      var PHONE    = /^[0-9+\s-]{7,20}$/;
      var MAX_CV   = 5 * 1024 * 1024;
      var CV_TYPES = ['pdf', 'doc', 'docx'];

      var LABELS = {
        first_name: 'First Name',
        last_name:  'Last Name',
        email:      'Email',
        phone:      'Phone Number',
        city:       'City',
        position:   'Position Applying For',
        resume:     'Resume'
      };

      var summary = document.getElementById('career-form-errors');

      function problem(field) {
        var name  = field.getAttribute('name');
        var value = (field.value || '').trim();

        if (name === 'first_name' || name === 'last_name' || name === 'city') {
          if (!value) { return 'This field is required.'; }
          if (!LETTERS.test(value)) { return 'Letters only - no numbers or symbols.'; }
          return null;
        }
        if (name === 'email') {
          if (!value) { return 'Please enter your email address.'; }
          if (!EMAIL.test(value)) { return 'Please enter a valid email address.'; }
          return null;
        }
        if (name === 'phone') {
          if (!value) { return 'Please enter your phone number.'; }
          if (!PHONE.test(value)) { return 'Digits only, at least 7 of them.'; }
          return null;
        }
        if (name === 'position') {
          if (!value) { return 'Please choose a position.'; }
          return null;
        }
        if (name === 'resume') {
          if (!field.files || !field.files.length) { return 'Please attach your resume.'; }
          var file = field.files[0];
          var ext  = file.name.split('.').pop().toLowerCase();
          if (CV_TYPES.indexOf(ext) === -1) { return 'Only PDF, DOC or DOCX files are accepted.'; }
          if (file.size > MAX_CV) { return 'The file must be smaller than 5MB.'; }
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

      var fields = ['first_name', 'last_name', 'email', 'phone', 'city', 'position', 'resume']
        .map(function (n) { return form.querySelector('[name="' + n + '"]'); })
        .filter(function (el) { return !!el; });

      // Re-check a field once the visitor has moved on from it.
      fields.forEach(function (field) {
        ['blur', 'change'].forEach(function (evt) {
          field.addEventListener(evt, function () {
            var message = problem(field);
            if (message) { showError(field, message); } else { clearError(field); }
          });
        });
      });

      form.addEventListener('submit', function (e) {
        var failures = [];

        fields.forEach(function (field) {
          var message = problem(field);
          if (message) {
            showError(field, message);
            failures.push({ field: field, text: LABELS[field.getAttribute('name')] + ': ' + message });
          } else {
            clearError(field);
          }
        });

        if (failures.length) {
          e.preventDefault();

          // Spell out every problem at the top of the form, so a blocked
          // submit is never mistaken for the button not working.
          if (summary) {
            var list = summary.querySelector('ul');
            list.innerHTML = '';
            failures.forEach(function (f) {
              var li = document.createElement('li');
              li.textContent = f.text;
              list.appendChild(li);
            });
            summary.style.display = 'block';
            summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
          } else {
            failures[0].field.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }

          failures[0].field.focus({ preventScroll: true });
          return;
        }

        if (summary) { summary.style.display = 'none'; }

        // Give feedback while the file uploads, and block a double submit.
        var button = form.querySelector('button[type="submit"]');
        if (button) {
          button.disabled = true;
          button.dataset.label = button.textContent;
          button.textContent = 'Submitting...';
        }
      });

      // "Apply Now" pre-selects that position and jumps to the form.
      document.querySelectorAll('.apply-now').forEach(function (link) {
        link.addEventListener('click', function () {
          var select = document.getElementById('position-select');
          if (select) {
            select.value = link.getAttribute('data-position') || '';
            clearError(select);
          }
        });
      });
    })();
  </script>
</body>

</html>
