 <header> 
      <section class="main_menu">
        <div class="container">
          <div class="row v-center">
            <div class="header-item item-left">
              <div class="logo">
                <a href="{{ route('frontend.index') }}"><img src="{{ asset('frontend/assets/images/home/catalyst-logo.webp')}}" alt="Catalyst Trustee logo"/></a>
              </div>
            </div>
            <!-- menu start here -->
            <div class="header-item item-center">
              <div class="menu-overlay"></div>
              <nav class="menu">
                <div class="mobile-menu-head">
                  <div class="go-back"><i class="fa fa-angle-left"></i></div>
                  <div class="current-menu-title"></div>
                  <div class="mobile-menu-close">×</div>
                </div>
                <ul class="menu-main">
                  <li><a href="{{ route('frontend.index') }}"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                  <li class="menu-item-has-children">
                    <a href="#">About <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="{{ route('frontend.company_overview') }}">Company Overview</a></li>
                        <li><a href="{{ route('frontend.leadership') }}">Our Leadership</a></li>
                        <li><a href="{{ route('frontend.group_companies') }}">Group Companies </a></li>
                        <!--<li><a href="#">Governance & Compliance </a></li>-->
                        <!-- <li><a href="our-landmark-transactions.html">Our Landmark Transactions</a></li> -->
                        <li><a href="{{ route('frontend.our_journey') }}">Our Journey </a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="services.html">Services <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu mega-menu row mega-menu-column-4 scrollbar" id="style-3">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            @foreach(($serviceMenu ?? []) as $cat)
                            <div class="col-md-2 list-item {{ $loop->last ? '' : 'border-right-one' }}">
                              <div class="mega-main-heading">
                                @if(!empty($cat['icon']))
                                <div class="icon"><img src="{{ asset('services/categories/'.$cat['icon']) }}" alt="icon"></div>
                                @endif
                                <h3><a href="#">{{ $cat['name'] }}</a></h3>
                              </div>
                              <ul>
                                @foreach(($cat['items'] ?? []) as $item)
                                <li><a href="{{ $item['link'] ?? '#' }}">{{ $item['title'] ?? '' }}</a></li>
                                @endforeach
                              </ul>
                            </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="#">Public Notice    <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="#">Notices & Announcements </a></li>
                        <li><a href="#">Regulatory Disclosures </a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="#">Grievance   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="#">Investor Grievance</a></li>
                        <li><a href="#">Contact for Support </a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="#">Newsletter <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="#">Articles</a></li>
                        <li><a href="#">News & Media </a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="#">Careers   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="#">Life at Catalyst</a></li>
                        <li><a href="#">Current Openings </a></li>
                        <li><a href="#">Internship / Graduate Opportunities</a></li>
                      </ul>
                    </div>
                  </li>

                  <!-- <li class="menu-item-has-children">
                    <a href="#">Accessibility   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="#">Accessibility Statement</a></li>
                        <li><a href="#">Accessibility Tools </a></li>
                      </ul>
                    </div>
                  </li> -->
                  

                  <li class="menu-item-has-children right-menu">
                    <a href="#">Contact   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="#">Office Locations</a></li>
                        <li><a href="#">Enquiry Form </a></li>
                        <li><a href="#">Contact Information </a></li>
                      </ul>
                    </div>
                  </li>
                  <!-- <li>
                    <a href="#">Announcements</a>
                  </li> -->
                </ul>
              </nav>
            </div><!-- menu end here -->
            <div class="header-item header-right-item item-right">
              <ul class="nav-icon">
                <li class="hvr-icon-push nav-search">
                  <a href="#" class="nav-icon-item icon-search">
                    <img src="{{ asset('frontend/assets/images/icons/search.svg')}}" class="hvr-icon">
                  </a>

                  <a class="btn-default" href="#">Get Started</a>
                </li>
              </ul>
              <!-- mobile menu trigger -->
              <div class="mobile-menu-trigger">
                <span></span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </header>