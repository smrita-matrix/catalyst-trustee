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
        $pageTitle = $notice->detail_banner_title ?: $notice->title;
        // The notice's own banner, then its page's, then the shared one.
        $bannerImage = $notice->detail_banner_image
            ? 'public-notice/detail-banner/' . $notice->detail_banner_image
            : (($category->banner_image ?: optional($banner)->background_image)
                ? 'public-notice/banner/' . ($category->banner_image ?: $banner->background_image)
                : null);
      @endphp

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if($bannerImage) style="background-image: url('{{ asset($bannerImage) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $pageTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li><a href="{{ route('frontend.notice_page', $category->slug) }}">{{ $category->page_title ?: $category->name }}</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                @if($parent)
                <li><a href="{{ $parent->detail_url }}">{{ $parent->detail_banner_title ?: $parent->title }}</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                @endif
                <li>{{ $pageTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="notice-detail-sec">
        <div class="container">
          @forelse($blocks as $block)
            @if($block['highlight'])
              @foreach($block['items'] as $item)
              <{{ $item->url ? 'a' : 'div' }} class="notice-detail-highlight" data-aos="fade-up" data-aos-duration="800"
                 @if($item->url) href="{{ $item->url }}" @unless($item->is_internal) target="_blank" rel="noopener noreferrer" @endunless @endif>
                <span class="notice-detail-icon"><i class="fa {{ $item->icon }}"></i></span>
                <span class="notice-detail-title">{{ $item->title }}</span>
                @if($item->url)<span class="notice-detail-arrow"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>@endif
              </{{ $item->url ? 'a' : 'div' }}>
              @endforeach
            @else
              <div class="notice-detail-group" data-aos="fade-up" data-aos-duration="800">
                @if($block['heading'])
                <h3 class="notice-detail-group-title">{{ $block['heading'] }}</h3>
                @endif
                <ul class="notice-detail-list">
                  @foreach($block['items'] as $item)
                  <li>
                    <{{ $item->url ? 'a' : 'span' }} class="notice-detail-row"
                       @if($item->url) href="{{ $item->url }}" @unless($item->is_internal) target="_blank" rel="noopener noreferrer" @endunless @endif>
                      <span class="notice-detail-icon"><i class="fa {{ $item->icon }}"></i></span>
                      <span class="notice-detail-title">{{ $item->title }}</span>
                      @if($item->url)<span class="notice-detail-arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span>@endif
                    </{{ $item->url ? 'a' : 'span' }}>
                  </li>
                  @endforeach
                </ul>
              </div>
            @endif
          @empty
            @include('frontend.public-notice.layouts._empty')
          @endforelse
        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
