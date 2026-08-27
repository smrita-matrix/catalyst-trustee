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
        $options = optional($content)->complaint_options ?: [
            'Non-Receipt of Interest / Principal',
            'Delay in Receipt of Interest / Principal',
            'Non-Receipt of Debentures',
            'Others',
        ];
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
                <li>{{ optional($content)->breadcrumb_child ?: $bTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="grievances-wrap">
        <div class="container">

          @if(optional($content)->intro_text)
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="grievances-front-text">
                <p>{{ $content->intro_text }}</p>
              </div>
            </div>
          </div>
          @endif

          <div class="grievances-form-box" id="grievance-form">

            @if(session('message'))
              <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('frontend.investor_grievance.store') }}" method="POST">
              @csrf

              <div class="grievances-section">
                <h3 data-aos="fade-up" data-aos-duration="1000">{{ optional($content)->holder_heading ?: 'Investor/Debenture Holder Details' }}</h3>

                <div class="row">

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Full Name <span>*</span></label>
                      <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required
                             pattern="[A-Za-z\s.'-]+" title="Letters only — no numbers.">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>PAN <span>*</span></label>
                      <input type="text" name="pan" class="form-control" value="{{ old('pan') }}" required
                             maxlength="10" placeholder="ABCDE1234F" style="text-transform:uppercase;">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Email Address <span>*</span></label>
                      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Mobile/Contact Number</label>
                      <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="+91">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Full Postal Address (as has been mention in Debenture Application) <span>*</span></label>
                      <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
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
                      <input type="text" name="issuer_name" class="form-control" value="{{ old('issuer_name') }}" required>
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Debenture Series Name</label>
                      <input type="text" name="series_name" class="form-control" value="{{ old('series_name') }}">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>ISIN / Multiple ISIN <span>*</span></label>
                      <input type="text" name="isin" class="form-control" value="{{ old('isin') }}" required>
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>No of Bonds held <span>*</span></label>
                      <input type="text" name="bonds_held" class="form-control" value="{{ old('bonds_held') }}" required>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group complaints-particulars">
                      <label>Complaints Particulars <span>*</span></label>

                      <div class="checkbox-list">
                        @foreach($options as $option)
                        <label class="checkbox-inline">
                          <input type="checkbox" name="complaint_types[]" value="{{ $option }}"
                            {{ in_array($option, (array) old('complaint_types', [])) ? 'checked' : '' }}> {{ $option }}
                        </label>
                        @endforeach
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Details of Grievance / Complaint (your complaint with brief description in 1000 characters) <span>*</span></label>
                      <textarea name="complaint_details" class="form-control grievance-textarea" maxlength="1000" required>{{ old('complaint_details') }}</textarea>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn-default">Submit</button>
                  </div>

                </div>
              </div>
            </form>

            @if(optional($content)->notes)
            <div class="grievances-note">
              <ul class="listing">
                @foreach($content->notes as $note)
                  <li>{!! $note !!}</li>
                @endforeach
              </ul>
            </div>
            @endif

          </div>

        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
