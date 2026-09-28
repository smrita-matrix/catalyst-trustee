
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

  @php $imgBase = 'service-uploads/documents/'; @endphp

  <div id="smooth-wrapper">
    <div id="smooth-content">

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($page)->banner_background_image) style="background-image: url('{{ asset($imgBase.'banner/'.$page->banner_background_image) }}');" @endif></div>
        <div class="container">
          @php $pageTitle = optional($page)->banner_title ?: optional($product ?? null)->name; @endphp
          <div class="breadcrumb-header-inner">
            <h1>{{ $pageTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                @if(optional($page)->banner_breadcrumb_parent)
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $page->banner_breadcrumb_parent }}</li>
                @endif
                @php $crumbChild = optional($page)->banner_breadcrumb_child ?: optional(optional($product ?? null)->serviceCategory)->name; @endphp
                @if($crumbChild)
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $crumbChild }}</li>
                @endif
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $pageTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {{-- The papers themselves, in the same cards every other list of
           documents on the site uses. --}}
      <section class="credit-rating-section">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="heading" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($page)->page_title ?: optional($product ?? null)->name }}</h2>
              </div>
            </div>
          </div>

          @if(trim(strip_tags(optional($page)->page_intro ?? '')) !== '')
          <div class="row">
            <div class="col-sm-12">
              <div class="grievances-front-text"><p>{{ $page->page_intro }}</p></div>
            </div>
          </div>
          @endif

          <div class="row bomsc-cards-row">
            @forelse($documents as $doc)
            @php $url = $doc->document_url; @endphp
            <div class="col-sm-3 col-xs-12">
              <{{ $url ? 'a' : 'div' }} class="bomsc-card" @if($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif>
                <div class="bomsc-card-icon"><i class="fa fa-file-text-o"></i></div>
                <h4 class="bomsc-card-title">{{ $doc->title }}</h4>
                @if($doc->description)<p class="bom-cre-rating-para">{{ $doc->description }}</p>@endif
                @if($url)
                <span class="bomsc-card-cta">View Document <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                @endif
              </{{ $url ? 'a' : 'div' }}>
            </div>
            @empty
            <div class="col-sm-12">
              <p class="text-center">Nothing has been published here yet.</p>
            </div>
            @endforelse
          </div>
        </div>
      </section>

      @include('components.frontend.service-disclaimer')
      @include('components.frontend.footer')
    </div>
  </div>

  @include('components.frontend.main-js')
</body>

</html>
