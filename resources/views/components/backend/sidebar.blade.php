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
    margin-top: 10px !important;
  }
  /* Safety net: always keep the sidebar on-screen and visible, whether or not
     the theme's scrollbar/reveal JS runs (fixes the "blank sidebar" on some pages). */
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper,
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main {
    position: fixed !important;
    left: 0 !important;
    transform: none !important;
    visibility: visible !important;
    opacity: 1 !important;
    display: block !important;
  }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main { position: static !important; }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links {
    display: block !important;
    left: 0 !important;
    visibility: visible !important;
    opacity: 1 !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
  }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main {
    min-height: calc(100vh - 130px) !important;
  }
  /* Neutralise SimpleBar's clipping wrappers (they get a 0/stale height on pages
     with rich-text editors). display:contents removes their boxes but keeps the
     DOM structure, so the theme's item styles still apply and the menu shows. */
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-wrapper,
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-mask,
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-offset,
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-content-wrapper {
    display: contents !important;
  }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-content {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    max-height: none !important;
    overflow: visible !important;
  }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-placeholder,
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links .simplebar-height-auto-observer-wrapper {
    display: none !important;
  }
  .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .sidebar-links li.sidebar-list {
    display: block !important;
    visibility: visible !important;
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
             
                <li class="sidebar-list {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="margin-top:10px;">
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

                @php
                    $companyOverviewActive = request()->routeIs('company-overview-banner-details.*') || request()->routeIs('company-overview-introduction-details.*') || request()->routeIs('company-overview-vision-mission-details.*');
                    $leadershipPageActive = request()->routeIs('leadership-banner-details.*') || request()->routeIs('leadership-content-details.*');
                    $groupCompaniesActive = request()->routeIs('group-companies-banner-details.*') || request()->routeIs('group-companies-overview-details.*') || request()->routeIs('group-companies-difc-details.*');
                    $ourJourneyActive = request()->routeIs('our-journey-banner-details.*') || request()->routeIs('our-journey-milestone-details.*');
                    $aboutUsActive = $companyOverviewActive || $leadershipPageActive || $groupCompaniesActive || $ourJourneyActive;
                @endphp
                <li class="sidebar-list {{ $aboutUsActive ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"> </i>
                  <a class="sidebar-link sidebar-title {{ $aboutUsActive ? 'active' : '' }}" href="#">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                    </svg>
                    <span>About Us</span>
                  </a>
                  <ul class="sidebar-submenu" @if ($aboutUsActive) style="display: block;" @endif>
                    <li>
                      <a class="submenu-title {{ $companyOverviewActive ? 'active' : '' }}" href="javascript:void(0)">Company Overview
                        <div class="according-menu"><i class="fa fa-angle-{{ $companyOverviewActive ? 'down' : 'right' }}"></i></div>
                      </a>
                      <ul class="nav-sub-childmenu submenu-content" @if ($companyOverviewActive) style="display: block;" @endif>
                        <li><a href="{{ route('company-overview-banner-details.index') }}" class="{{ request()->routeIs('company-overview-banner-details.*') ? 'active' : '' }}">Banner Details</a></li>
                        <li><a href="{{ route('company-overview-introduction-details.index') }}" class="{{ request()->routeIs('company-overview-introduction-details.*') ? 'active' : '' }}">Introduction</a></li>
                        <li><a href="{{ route('company-overview-vision-mission-details.index') }}" class="{{ request()->routeIs('company-overview-vision-mission-details.*') ? 'active' : '' }}">Vision &amp; Mission</a></li>
                      </ul>
                    </li>
                    <li>
                      <a class="submenu-title {{ $leadershipPageActive ? 'active' : '' }}" href="javascript:void(0)">Leadership
                        <div class="according-menu"><i class="fa fa-angle-{{ $leadershipPageActive ? 'down' : 'right' }}"></i></div>
                      </a>
                      <ul class="nav-sub-childmenu submenu-content" @if ($leadershipPageActive) style="display: block;" @endif>
                        <li><a href="{{ route('leadership-banner-details.index') }}" class="{{ request()->routeIs('leadership-banner-details.*') ? 'active' : '' }}">Banner Details</a></li>
                        <li><a href="{{ route('leadership-content-details.index') }}" class="{{ request()->routeIs('leadership-content-details.*') ? 'active' : '' }}">Content (Board &amp; Team)</a></li>
                      </ul>
                    </li>
                    <li>
                      <a class="submenu-title {{ $groupCompaniesActive ? 'active' : '' }}" href="javascript:void(0)">Group Companies
                        <div class="according-menu"><i class="fa fa-angle-{{ $groupCompaniesActive ? 'down' : 'right' }}"></i></div>
                      </a>
                      <ul class="nav-sub-childmenu submenu-content" @if ($groupCompaniesActive) style="display: block;" @endif>
                        <li><a href="{{ route('group-companies-banner-details.index') }}" class="{{ request()->routeIs('group-companies-banner-details.*') ? 'active' : '' }}">Banner Details</a></li>
                        <li><a href="{{ route('group-companies-overview-details.index') }}" class="{{ request()->routeIs('group-companies-overview-details.*') ? 'active' : '' }}">Group Overview</a></li>
                        <li><a href="{{ route('group-companies-difc-details.index') }}" class="{{ request()->routeIs('group-companies-difc-details.*') ? 'active' : '' }}">DIFC Services</a></li>
                      </ul>
                    </li>
                    <li>
                      <a class="submenu-title {{ $ourJourneyActive ? 'active' : '' }}" href="javascript:void(0)">Our Journey
                        <div class="according-menu"><i class="fa fa-angle-{{ $ourJourneyActive ? 'down' : 'right' }}"></i></div>
                      </a>
                      <ul class="nav-sub-childmenu submenu-content" @if ($ourJourneyActive) style="display: block;" @endif>
                        <li><a href="{{ route('our-journey-banner-details.index') }}" class="{{ request()->routeIs('our-journey-banner-details.*') ? 'active' : '' }}">Banner Details</a></li>
                        <li><a href="{{ route('our-journey-milestone-details.index') }}" class="{{ request()->routeIs('our-journey-milestone-details.*') ? 'active' : '' }}">Milestones in Progress</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>

                @php
                    $categoryActive = request()->routeIs('service-category.*');
                    $productActive = request()->routeIs('product-category.*');
                    $productPageActive = request()->routeIs('product-services.*')
                        || request()->routeIs('service-layout1.*')
                        || request()->routeIs('service-layout2.*')
                        || request()->routeIs('service-layout3.*')
                        || request()->routeIs('service-fif.*')
                        || request()->routeIs('product-page.*');
                    $servicesActive = $categoryActive || $productActive || $productPageActive || request()->routeIs('layout-guide');
                @endphp
                <li class="sidebar-list {{ $servicesActive ? 'active' : '' }}">
                  <i class="fa fa-thumb-tack"> </i>
                  <a class="sidebar-link sidebar-title {{ $servicesActive ? 'active' : '' }}" href="#">
                    <svg class="stroke-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-icons') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                      <use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-icons') }}"></use>
                    </svg>
                    <span>Services</span>
                  </a>
                  <ul class="sidebar-submenu" @if ($servicesActive) style="display: block;" @endif>
                    <li><a href="{{ route('service-category.index') }}" class="{{ $categoryActive ? 'active' : '' }}">Service Categories</a></li>
                    <li><a href="{{ route('product-category.index') }}" class="{{ $productActive ? 'active' : '' }}">Product Categories</a></li>
                    <li><a href="{{ route('product-services.index') }}" class="{{ $productPageActive ? 'active' : '' }}">Product Services</a></li>
                    <li><a href="{{ route('layout-guide') }}" class="{{ request()->routeIs('layout-guide') ? 'active' : '' }}">Layout Guide</a></li>
                  </ul>
                </li>
              </ul>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </div>
          </nav>
        </div>


        