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
