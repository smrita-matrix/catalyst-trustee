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

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if($page->banner_url) style="background-image: url('{{ $page->banner_url }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $page->title }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $page->breadcrumb_child ?: $page->title }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      @php
        // Only sections with something in them, and numbered for the side index.
        $sections = collect($page->sections ?? [])
            ->map(fn ($s) => ['heading' => trim($s['heading'] ?? ''), 'body' => trim($s['body'] ?? '')])
            ->filter(fn ($s) => $s['heading'] !== '' || $s['body'] !== '')
            ->values();
      @endphp

      <section class="policy-sec">
        <div class="container">
          <div class="row">

            @if($sections->count() > 1)
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="policy-index">
                <h4>On this page</h4>
                <ul>
                  @foreach($sections as $i => $section)
                    @if($section['heading'] !== '')
                      <li><a href="#policy-{{ $i + 1 }}">{{ $section['heading'] }}</a></li>
                    @endif
                  @endforeach
                </ul>
              </div>
            </div>
            @endif

            <div class="{{ $sections->count() > 1 ? 'col-md-8 col-sm-8' : 'col-md-12 col-sm-12' }} col-xs-12">
              <div class="policy-body">

                @if($page->effective_on)
                  <p class="policy-updated">Last updated {{ $page->effective_on->format('d F Y') }}</p>
                @endif

                @if($page->intro_text)
                  <div class="policy-intro">
                    @foreach(preg_split('/\R\s*\R/', trim($page->intro_text)) as $para)
                      <p>{!! nl2br(e($para)) !!}</p>
                    @endforeach
                  </div>
                @endif

                @forelse($sections as $i => $section)
                  <div class="policy-block" id="policy-{{ $i + 1 }}" data-section-tab>
                    @if($section['heading'] !== '')
                      <h3>{{ $section['heading'] }}</h3>
                    @endif
                    {{-- Blank lines separate paragraphs; a line starting with "- " becomes a bullet. --}}
                    @php
                      $paras = preg_split('/\R\s*\R/', $section['body']);
                    @endphp
                    @foreach($paras as $para)
                      @php $para = trim($para); @endphp
                      @continue($para === '')
                      @if(preg_match('/^\s*-\s+/m', $para) && str_starts_with(ltrim($para), '- '))
                        <ul class="policy-list">
                          @foreach(preg_split('/\R/', $para) as $line)
                            @php $line = trim(preg_replace('/^\s*-\s+/', '', $line)); @endphp
                            @if($line !== '')<li>{!! $line !!}</li>@endif
                          @endforeach
                        </ul>
                      @else
                        <p>{!! nl2br($para) !!}</p>
                      @endif
                    @endforeach
                  </div>
                @empty
                  <p>This page has not been published yet.</p>
                @endforelse

              </div>
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
