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
