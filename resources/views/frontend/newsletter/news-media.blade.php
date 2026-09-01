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

      @php $bTitle = optional($banner)->title ?: 'News & Media'; @endphp

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('news-media-uploads/banner/'.$banner->background_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $bTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_parent ?: 'Newsletter' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($banner)->breadcrumb_child ?: $bTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="blog-listing-section">
        <div class="container">

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ optional($banner)->section_heading ?: $bTitle }}</h2>
              </div>
            </div>
          </div>

          <div class="row">
            @forelse($items as $item)
            @php $url = $item->read_more_url; @endphp
            <div class="col-sm-6 col-md-4">
              <article class="blog-card">
                <div class="blog-image">
                  <a href="{{ $url ?: '#' }}" @if($url) target="_blank" rel="noopener noreferrer" @endif>
                    <img src="{{ $item->image_url ?: asset('frontend/assets/images/home/news-media-img-1.webp') }}" alt="{{ $item->title }}">
                  </a>
                  @if($item->category)
                  <span class="blog-category">
                    <i class="fa fa-lightbulb-o"></i> {{ $item->category }}
                  </span>
                  @endif
                </div>
                <div class="blog-content">
                  <h3>
                    <a href="{{ $url ?: '#' }}" @if($url) target="_blank" rel="noopener noreferrer" @endif>{{ $item->title }}</a>
                  </h3>
                  @if(trim($item->description ?? '') !== '')
                  <div class="blog-summary">
                    @foreach(preg_split('/\R\s*\R/', trim($item->description)) as $para)
                      @php $para = trim($para); @endphp
                      @if($para !== '')<p>{{ $para }}</p>@endif
                    @endforeach
                  </div>
                  @endif
                  @if($url)
                  <a href="{{ $url }}" class="blog-read-more" target="_blank" rel="noopener noreferrer">
                    Read More <i class="fa fa-long-arrow-right"></i>
                  </a>
                  @endif
                </div>
              </article>
            </div>
            @empty
            <div class="col-md-12">
              <p class="text-center">No news has been published yet.</p>
            </div>
            @endforelse
          </div>

        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
