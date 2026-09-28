
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
        $bTitle    = $page->banner_title ?: 'Investor Grievances';
        $pageTitle = $page->breadcrumb_child ?: $page->title;

        // The complaint tick-boxes and the address the form is sent to are the
        // same wherever a form appears, so they stay on the settings row.
        $options = optional($content)->complaint_options ?: [
            'Non-Receipt of Interest / Principal',
            'Delay in Receipt of Interest / Principal',
            'Non-Receipt of Debentures',
            'Others',
        ];

        // Which form was being filled in when something was rejected.
        $failed   = old('_form');
        $hasForm  = in_array($page->form_type, ['sebi', 'non_sebi'], true);
        $contacts = collect($page->contacts ?? [])
            ->filter(fn ($c) => trim(($c['label'] ?? '') . ($c['value'] ?? '')) !== '');
        $hasAside = $contacts->count() || trim(strip_tags($page->note ?? '')) !== ''
                    || $page->document_file || $page->external_link;
      @endphp

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if($page->banner_image) style="background-image: url('{{ asset('grievance/banner/'.$page->banner_image) }}');" @endif></div>
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

      <section class="grievances-wrap{{ $page->form_type === 'non_sebi' ? ' grievances-wrap--alt' : '' }}" id="grievance-{{ $page->slug }}" data-section-tab>
        <div class="container">

          <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
            <h2>{{ $page->heading ?: $page->title }}</h2>
          </div>

          @if($page->intro)
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="grievances-front-text"><p>{{ $page->intro }}</p></div>
            </div>
          </div>
          @endif

          <div class="row grievance-layout">
            {{-- With no form to fill in, the wording takes the full width unless
                 there is something to sit beside it. --}}
            <div class="{{ $hasForm || $hasAside ? 'col-md-8' : 'col-md-12' }} col-sm-12 col-xs-12">

              @if(trim(strip_tags($page->body ?? '')) !== '')
              <div class="grievance-body">
                {!! $page->body !!}
              </div>
              @endif

              @if($hasForm)
              <div class="grievances-form-box">
                @if($errors->any() && $failed === $page->form_type)
                <div class="alert alert-danger">
                  <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
                @endif

                @include('frontend.grievance.partials._form-' . ($page->form_type === 'sebi' ? 'sebi' : 'non-sebi'))
              </div>
              @endif

            </div>

            @if($hasForm || $hasAside)
            <div class="col-md-4 col-sm-12 col-xs-12">
              <aside class="grievance-aside">

                @if($contacts->count())
                <div class="grievance-officer">
                  @foreach($contacts as $c)
                  @php $kind = $c['kind'] ?? 'text'; $value = trim($c['value'] ?? ''); @endphp
                  <p>
                    @if(trim($c['label'] ?? '') !== '')<strong>{{ $c['label'] }}</strong>@if($value !== '') &ndash; @endif @endif
                    @if($kind === 'email')
                      <a href="mailto:{{ $value }}">{{ $value }}</a>
                    @elseif($kind === 'phone')
                      <a href="tel:{{ preg_replace('/\s+/', '', $value) }}">{{ $value }}</a>
                    @else
                      {{ $value }}
                    @endif
                  </p>
                  @endforeach
                </div>
                @endif

                @if($page->document_file || $page->external_link)
                @php
                  $docUrl = $page->document_file
                      ? asset('grievance/documents/' . $page->document_file)
                      : $page->external_link;
                @endphp
                <div class="grievance-doc">
                  <a class="btn-default" href="{{ $docUrl }}" target="_blank" rel="noopener noreferrer">
                    {{ $page->document_label ?: 'View Document' }}
                  </a>
                </div>
                @endif

                @if(trim(strip_tags($page->note ?? '')) !== '')
                <div class="grievance-note">{!! $page->note !!}</div>
                @endif

              </aside>
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
