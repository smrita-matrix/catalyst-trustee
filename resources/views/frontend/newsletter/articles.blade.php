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

  @php $bTitle = optional($banner)->title ?: 'Articles'; @endphp

  <div id="smooth-wrapper">
    <div id="smooth-content">

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($banner)->background_image) style="background-image: url('{{ asset('newsletter/banner/'.$banner->background_image) }}');" @endif></div>
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

      <section class="newsletter-archive-sec">
        <div class="container">
          @forelse($blocks as $year => $items)
          <div class="nla-month-block">
            <div class="nla-month-title">
              <span class="nla-month-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
              <h3>{{ $year }}</h3>
              <span class="nla-month-line"></span>
            </div>

            <div class="row nla-row">
              @foreach($items as $article)
              <div class="col-md-3 col-sm-6 nla-col">
                <div class="nla-card nla-card-latest">
                  <span class="nla-date-tag">{{ $article->title }}</span>
                  <div class="nla-thumb-wrap">
                    @if($article->image_url)
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="img-responsive">
                    @endif
                  </div>
                  @if($article->pdf_url)
                  <a href="{{ $article->pdf_url }}" target="_blank" rel="noopener noreferrer" class="nla-download-btn" title="Download PDF">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    <span class="nla-download-label">PDF</span>
                  </a>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @empty
          <div class="nla-month-block">
            <div class="nla-month-title">
              <span class="nla-month-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
              <h3>Articles</h3>
              <span class="nla-month-line"></span>
            </div>
            <p>No articles have been published yet.</p>
          </div>
          @endforelse
        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
       @include('components.frontend.main-js')
</body>

</html>
