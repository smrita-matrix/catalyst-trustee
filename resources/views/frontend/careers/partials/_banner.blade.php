{{-- The page banner, shared by both Careers pages.

     The picture comes from the dashboard's Careers settings; each page passes
     its own heading in $pageTitle, and sits under Careers in the trail. --}}
      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg" @if(optional($content)->banner_image) style="background-image: url('{{ asset('career-uploads/banner/'.$content->banner_image) }}');" @endif></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $pageTitle }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ optional($content)->breadcrumb_child ?: 'Careers' }}</li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $pageTitle }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
