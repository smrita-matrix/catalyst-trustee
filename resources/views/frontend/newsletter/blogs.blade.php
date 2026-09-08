{{-- The blog: every post, newest first. --}}
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
        <div class="breadcrumb-header-bg"></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>Blog</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>Articles</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>Blog</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="blog-list-sec">
        <div class="container">

          @if($blogs->count())
          <div class="row blog-list-row">
            @foreach($blogs as $blog)
            <div class="col-sm-4 col-xs-12">
              <a class="blog-card" href="{{ $blog->url }}">
                <span class="blog-card-media">
                  @if($blog->image_url)
                  <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" loading="lazy">
                  @endif
                </span>
                <span class="blog-card-body">
                  @if($blog->published_on)
                  <span class="blog-card-date">
                    <i class="fa fa-calendar" aria-hidden="true"></i> {{ $blog->published_on->format('d M Y') }}
                  </span>
                  @endif
                  <h3 class="blog-card-title">{{ $blog->title }}</h3>
                  <span class="blog-card-text">{{ $blog->summary }}</span>
                  <span class="blog-card-cta">Read More <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </span>
              </a>
            </div>
            @endforeach
          </div>
          @else
          <div class="row">
            <div class="col-sm-12">
              <p class="text-center">There are no posts here yet. Please check back soon.</p>
            </div>
          </div>
          @endif

        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
