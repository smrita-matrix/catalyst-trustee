      <section class="careers-custom-sec" id="life-at-catalyst" data-section-tab>
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                @if(optional($content)->intro_heading)<h2>{{ $content->intro_heading }}</h2>@endif
                {{-- A blank line in the admin box starts a new paragraph. --}}
                @foreach(preg_split('/\R\s*\R/', trim((string) optional($content)->intro_text)) as $para)
                  @if(trim($para) !== '')<p>{{ trim($para) }}</p>@endif
                @endforeach
              </div>
            </div>
          </div>

        </div>
      </section>
