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
                        <li><a href="company-overview.html">Company Overview</a></li>
                        <li><a href="leadership.html">Our Leadership</a></li>
                        <li><a href="group-companies.html">Group Companies </a></li>
                        <!--<li><a href="#">Governance & Compliance </a></li>-->
                        <li><a href="our-landmark-transactions.html">Our Landmark Transactions</a></li>
                        <li><a href="our-journey.html">Our Journey </a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="services.html">Services <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu mega-menu row mega-menu-column-4 scrollbar" id="style-3">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-2 list-item border-right-one">
                              <div class="mega-main-heading">
                                <div class="icon"><img src="{{ asset('frontend/assets/images/icons/repairing-service.png')}}" alt="icon"></div>
                                <h3><a href="#">Sebi Regulated Services</a></h3>
                              </div>
                              <ul>
                                <li><a href="#">Debenture Trustee Services (Listed)</a></li> 
                                <li><a href="#">Securatization (Listed PTC) </a></li> 
                                <li><a href="#">Alternative Investment Funds  </a></li> 
                              </ul>
                            </div>
                            <div class="col-md-2 list-item border-right-one">
                              <div class="mega-main-heading">
                                <div class="icon"><img src="{{ asset('frontend/assets/images/icons/customer.png')}}" alt="icon"></div>
                                <h3><a href="#">Non Sebi Regulated Services</a></h3>
                              </div>                              
                              <ul>
                                <li><a href="#">Debenture Trustee (Unlisted) </a></li>
                                <li><a href="#">Securatization Trustee (DA/Unlisted PTC) </a></li>
                                <li><a href="#">Security Trustee </a></li>
                                <li><a href="#">Employee Benefit Trust Management </a></li>
                                <li><a href="#">Share Monitoring </a></li>
                                <li><a href="#">Facility Agent  </a></li>
                                <li><a href="#">Software Escrow Service </a></li>
                                <li><a href="#">Safe Custody of Documents </a></li>
                                <li><a href="#">Trustee For Public Deposits </a></li>
                                <li><a href="#">Escrow Agent </a></li>
                              </ul>
                            </div>
                            <div class="col-md-2 list-item">
                              <div class="mega-main-heading">
                                <div class="icon"><img src="{{ asset('frontend/assets/images/icons/settings.png')}}" alt="icon"></div>
                                <h3><a href="#">GIFT City Services</a></h3>
                              </div> 
                              <ul>
                                <li><a href="#">Introduction</a></li>
                                <li><a href="#">AIF in GIFT City</a></li> 
                                <li><a href="#">Facility Agent </a></li>
                                <li><a href="#">Bond/Debenture Trustee </a></li>
                                <li><a href="#">Corporate Services </a></li>
                                <li><a href="#">Family Investment Funds </a></li>
                                <!--<li><a href="#">Grievance Redressal – Contact us</a></li> -->
                              </ul>
                            </div>
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