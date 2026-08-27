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
        $pageTitle = $category->banner_title ?: ($category->page_title ?: $category->name);
        // Second breadcrumb crumb: the mega-menu column this page sits under.
        $topParent = $category->parent;
        while ($topParent && $topParent->parent_id) {
            $topParent = $topParent->parent;
        }
        // This page's own banner, falling back to the shared Public Notice banner.
        $bannerImage = $category->banner_image ?: optional($banner)->background_image;
      @endphp

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if($bannerImage) style="background-image: url('{{ asset('public-notice/banner/'.$bannerImage) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $pageTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($topParent)->name ?: 'Public Notice' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $pageTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {{-- The design chosen for this page in the admin (Public Notice > Menu & Pages). --}}
      @include($category->layout_view)

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
