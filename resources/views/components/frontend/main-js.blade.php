{{-- ================= Site search modal =================
     Opens when the header magnifier is clicked. Results appear as the
     visitor types; the icon is still a plain link to /search so the
     feature works with JavaScript unavailable. --}}
<div class="site-search-modal" id="site-search-modal" aria-hidden="true">
  <div class="site-search-backdrop" data-search-close></div>

  <div class="site-search-dialog" role="dialog" aria-modal="true" aria-label="Search">
    <div class="site-search-head">
      <h4>Search</h4>
      <button type="button" class="site-search-close" data-search-close aria-label="Close search">&times;</button>
    </div>

    <form action="javascript:void(0)" method="GET" role="search" id="site-search-form" onsubmit="return false;">
      <div class="site-search-field">
        <input type="search" name="q" id="site-search-input" autocomplete="off"
               placeholder="Search for anything..." aria-label="Search the website">
        <button type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
      </div>
    </form>

    <div class="site-search-results" id="site-search-results"></div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="{{ versioned_asset('frontend/assets/js/owl.carousel.js')}}"></script>
    <script src="{{ versioned_asset('frontend/assets/js/life-slider.js') }}"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="{{ versioned_asset('frontend/assets/js/menu.js') }}"></script>
    <script src="{{ versioned_asset('frontend/assets/js/menu-subsub.js') }}"></script>
    <script src="{{ versioned_asset('frontend/assets/js/notice-table.js') }}"></script>
    <script src="{{ versioned_asset('frontend/assets/js/grievance-validate.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/jquery.waypoints.min.js"></script>
    <script src="https://ciromattia.github.io/jquery.counterup/jquery.counterup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>


    <script src="{{ versioned_asset('frontend/assets/js/graph.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <script src="https://oriafenestrations.in/js/gsap-scroll-to-plugin.js"></script>
    <script src="https://oriafenestrations.in/js/gsap-scroll-smoother.js"></script>

    <script src="{{ versioned_asset('frontend/assets/js/custom.js')}}"></script>
    <script src="{{ versioned_asset('frontend/assets/js/section-scroll.js') }}"></script>
    <script src="{{ versioned_asset('frontend/assets/js/site-search.js') }}"></script>


    <script>
     
    </script>