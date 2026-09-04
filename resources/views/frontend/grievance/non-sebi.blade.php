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

      @php
        $bTitle  = optional($content)->banner_title ?: 'Investor Grievances';
        $pageTitle = 'For Services Not Regulated By SEBI';
        $options = optional($content)->complaint_options ?: [
            'Non-Receipt of Interest / Principal',
            'Delay in Receipt of Interest / Principal',
            'Non-Receipt of Debentures',
            'Others',
        ];
        // Which form was being filled in when something was rejected.
        $failed = old('_form');
      @endphp

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($content)->banner_image) style="background-image: url('{{ asset('grievance/banner/'.$content->banner_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $bTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $pageTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>


      {{-- =============== For Services Not Regulated By SEBI =============== --}}
      <section class="grievances-wrap grievances-wrap--alt" id="grievance-non-sebi" data-section-tab>
        <div class="container">

          <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
            <h2>{{ optional($content)->non_sebi_heading ?: 'For Services Not Regulated By SEBI' }}</h2>
          </div>

          @if(optional($content)->non_sebi_intro)
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="grievances-front-text"><p>{{ $content->non_sebi_intro }}</p></div>
            </div>
          </div>
          @endif

          <div class="row grievance-layout">
            <div class="col-md-8 col-sm-12 col-xs-12">
              <div class="grievances-form-box">

            @if($errors->any() && $failed === 'non_sebi')
              <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
              </div>
            @endif

            <form action="{{ route('frontend.investor_grievance.store') }}" method="POST">
              @csrf
              <input type="hidden" name="_form" value="non_sebi">

              <div class="grievances-section">
                <h3 data-aos="fade-up" data-aos-duration="1000">{{ optional($content)->holder_heading ?: 'Investor/Debenture Holder Details' }}</h3>

                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Full Name <span>*</span></label>
                      <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required
                             pattern="[A-Za-z\s.'-]+" title="Letters only — no numbers." placeholder="e.g. Jane A. Smith">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>PAN <span>*</span></label>
                      <input type="text" name="pan" class="form-control" value="{{ old('pan') }}" required
                             maxlength="10" placeholder="e.g. ABCDE1234F" style="text-transform:uppercase;">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Email Address <span>*</span></label>
                      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. name@example.com">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Mobile/Contact Number</label>
                      <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="e.g. +91 98765 43210">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Full Postal Address (as mentioned in the Debenture Application) <span>*</span></label>
                      <textarea name="address" class="form-control" rows="3" required placeholder="Flat / house number, street, area, city, state and PIN code">{{ old('address') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grievances-section">
                <h3 data-aos="fade-up" data-aos-duration="1000">{{ optional($content)->instrument_heading ?: 'Instrument Details & Grievance' }}</h3>

                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Debenture Issuer Name <span>*</span></label>
                      <input type="text" name="issuer_name" class="form-control" value="{{ old('issuer_name') }}" required placeholder="e.g. Example Finance Limited">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Debenture Series Name</label>
                      <input type="text" name="series_name" class="form-control" value="{{ old('series_name') }}" placeholder="e.g. Series A / Tranche II (leave blank if not applicable)">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>ISIN / Multiple ISIN <span>*</span></label>
                      <input type="text" name="isin" class="form-control" value="{{ old('isin') }}" required placeholder="e.g. INE001A01036">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>No of Bonds held <span>*</span></label>
                      <input type="number" name="bonds_held" class="form-control" value="{{ old('bonds_held') }}" min="1" required placeholder="e.g. 50">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Complaints Particulars <span>*</span></label>
                      <div class="grievance-checks">
                        @foreach($options as $option)
                        <label class="grievance-check">
                          <input type="checkbox" name="complaint_types[]" value="{{ $option }}"
                                 {{ in_array($option, (array) old('complaint_types', []), true) ? 'checked' : '' }}>
                          <span>{{ $option }}</span>
                        </label>
                        @endforeach
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Details of Grievance / Complaint <span>*</span></label>
                      <textarea name="complaint_details" class="form-control" rows="4" maxlength="1000" required
                                placeholder="Describe your complaint, within 1000 characters">{{ old('complaint_details') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grievance-actions">
                <button type="submit" class="btn-default">Submit</button>
              </div>
            </form>
              </div>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
              <aside class="grievance-aside">

            @if(optional($content)->non_sebi_officer_name || optional($content)->non_sebi_officer_email)
            <div class="grievance-officer">
              @if($content->non_sebi_officer_name)
                <p><strong>Grievance Officer:</strong> {{ $content->non_sebi_officer_name }}</p>
              @endif
              @if($content->non_sebi_officer_phone)
                <p>Phone: <a href="tel:{{ preg_replace('/\s+/', '', $content->non_sebi_officer_phone) }}">{{ $content->non_sebi_officer_phone }}</a></p>
              @endif
              @if($content->non_sebi_officer_email)
                <p>Email: <a href="mailto:{{ $content->non_sebi_officer_email }}">{{ $content->non_sebi_officer_email }}</a></p>
              @endif
            </div>
            @endif

            @if(optional($content)->non_sebi_note)
            <div class="grievance-disclaimer">
              <p>{{ $content->non_sebi_note }}</p>
            </div>
            @endif

              </aside>
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
