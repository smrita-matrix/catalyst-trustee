<!-- Page Body Start-->
<style>
  /* Catalyst logo header sizing – keep menu clear of the logo */
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .logo-wrapper {
    height: 130px !important;
    padding: 12px 30px !important;
  }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links {
    height: calc(100vh - 200px) !important;
  }
  /* push the first menu item clear of the logo */
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links > li.back-btn + li.sidebar-list {
    margin-top: 62px !important;
  }
</style>
 <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper" data-layout="stroke-svg">
          <div class="logo-wrapper" style="display:flex !important; align-items:center !important; justify-content:center !important; height:130px !important; padding:12px 30px !important; overflow:hidden !important;">
		  	<a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; justify-content:center;">
				<img class="img-fluid" src="{{ asset('admin/assets/images/logo/catalyst-logo.webp') }}" alt="Catalyst" style="max-height:105px !important; max-width:100% !important; width:auto !important; object-fit:contain;">
			</a>
		  <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
          </div>
          <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid" src="{{ asset('admin/assets/images/logo/favicon.webp') }}" alt="Catalyst" ></a></div>
          <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
              <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid" src="{{ asset('admin/assets/images/logo/favicon.webp') }}" alt="Catalyst"></a>
                  <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                </li>
             
                <li class="sidebar-list {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="margin-top:60px;">
                  <i class="fa fa-thumb-tack"> </i>
                  <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#fill-home') }}"></use>
                    </svg>
                    <span class="lan-3">Dashboard</span>
                  </a>
                </li>

                <li class="sidebar-list {{ request()->routeIs('banner-details.*') || request()->routeIs('marquee-inner.*') || request()->routeIs('about-catalyst-details.*') || request()->routeIs('why-choose-details.*') || request()->routeIs('sebi-service-details.*') || request()->routeIs('non-sebi-service-details.*') || request()->routeIs('gift-city-details.*') || request()->routeIs('leadership-details.*') || request()->routeIs('business-performance-details.*') || request()->routeIs('landmark-details.*') || request()->routeIs('proofs-details.*') || request()->routeIs('cta-details.*') || request()->routeIs('footer-details.*') ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"> </i>
                  <a class="sidebar-link sidebar-title" href="#">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-icons') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-icons') }}"></use>
                    </svg>
                    <span>Home page</span>
                  </a>
                  <ul class="sidebar-submenu">
                    <li><a href="{{ route('banner-details.index') }}" class="{{ request()->routeIs('banner-details.*') ? 'active' : '' }}">Banner Details</a></li>
                    <li><a href="{{ route('marquee-inner.index') }}" class="{{ request()->routeIs('marquee-inner.*') ? 'active' : '' }}">Marquee Items</a></li>
                    <li><a href="{{ route('about-catalyst-details.index') }}" class="{{ request()->routeIs('about-catalyst-details.*') ? 'active' : '' }}">About Catalyst</a></li>
                    <li><a href="{{ route('why-choose-details.index') }}" class="{{ request()->routeIs('why-choose-details.*') ? 'active' : '' }}">Why Choose Catalyst</a></li>
                    <li><a href="{{ route('sebi-service-details.index') }}" class="{{ request()->routeIs('sebi-service-details.*') ? 'active' : '' }}">SEBI Services</a></li>
                    <li><a href="{{ route('non-sebi-service-details.index') }}" class="{{ request()->routeIs('non-sebi-service-details.*') ? 'active' : '' }}">Activities Outside SEBI</a></li>
                    <li><a href="{{ route('gift-city-details.index') }}" class="{{ request()->routeIs('gift-city-details.*') ? 'active' : '' }}">GIFT City</a></li>
                    <li><a href="{{ route('leadership-details.index') }}" class="{{ request()->routeIs('leadership-details.*') ? 'active' : '' }}">Leadership &amp; Numbers</a></li>
                    <li><a href="{{ route('business-performance-details.index') }}" class="{{ request()->routeIs('business-performance-details.*') ? 'active' : '' }}">Business Performance</a></li>
                    <li><a href="{{ route('landmark-details.index') }}" class="{{ request()->routeIs('landmark-details.*') ? 'active' : '' }}">Landmark Transactions</a></li>
                    <li><a href="{{ route('proofs-details.index') }}" class="{{ request()->routeIs('proofs-details.*') ? 'active' : '' }}">Proofs / Recognition</a></li>
                    <li><a href="{{ route('cta-details.index') }}" class="{{ request()->routeIs('cta-details.*') ? 'active' : '' }}">CTA Section</a></li>
                    <li><a href="{{ route('footer-details.index') }}" class="{{ request()->routeIs('footer-details.*') ? 'active' : '' }}">Footer</a></li>

                  </ul>
                </li>
                

 
                 


              </ul>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </div>
          </nav>
        </div>


        