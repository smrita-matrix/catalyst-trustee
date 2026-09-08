{{-- One blog post. --}}
<!DOCTYPE html>
<html lang="en">

<head>
  @include('components.frontend.head')
  <title>{{ $blog->title }} | Catalyst Trusteeship Limited</title>
  <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($blog->summary), 155) }}">
</head>

<body>
  <div class="body-overlay"></div>
  <header>
    @include('components.frontend.header')
  </header>

  <div id="smooth-wrapper">
    <div id="smooth-content">

      <section class="breadcrumb-bg-sec">
        {{-- The post's own picture is shown below, at a size it can be read
             at; behind the title it only fights with the words. --}}
        <div class="breadcrumb-header-bg"></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $blog->title }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li><a href="{{ route('frontend.blogs') }}">Blog</a></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="blog-post-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12">

              <div class="blog-post-meta">
                @if($blog->published_on)
                <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $blog->published_on->format('d F Y') }}</span>
                @endif
                @if($blog->author)
                <span><i class="fa fa-user-o" aria-hidden="true"></i> {{ $blog->author }}</span>
                @endif
              </div>

              @if($blog->image_url)
              <div class="blog-post-image">
                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
              </div>
              @endif

              {{-- Written in the dashboard, where basic HTML is allowed. --}}
              <div class="blog-post-body">
                {!! $blog->body !!}
              </div>

              <div class="blog-post-foot">
                <a href="{{ route('frontend.blogs') }}" class="blog-back-link">
                  <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> All posts
                </a>
              </div>

            </div>
          </div>

          @if($more->count())
          <div class="row">
            <div class="col-sm-12">
              <div class="heading heading-center blog-more-heading">
                <h2>More from the blog</h2>
              </div>
            </div>
          </div>
          <div class="row blog-list-row">
            @foreach($more as $other)
            <div class="col-sm-4 col-xs-12">
              <a class="blog-card" href="{{ $other->url }}">
                <span class="blog-card-media">
                  @if($other->image_url)
                  <img src="{{ $other->image_url }}" alt="{{ $other->title }}" loading="lazy">
                  @endif
                </span>
                <span class="blog-card-body">
                  @if($other->published_on)
                  <span class="blog-card-date"><i class="fa fa-calendar" aria-hidden="true"></i> {{ $other->published_on->format('d M Y') }}</span>
                  @endif
                  <h3 class="blog-card-title">{{ $other->title }}</h3>
                  <span class="blog-card-cta">Read More <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </span>
              </a>
            </div>
            @endforeach
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
