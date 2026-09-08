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
