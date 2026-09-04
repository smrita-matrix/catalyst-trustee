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
                    <a class="menu-opener">About <i class="fa fa-angle-down"></i></a>
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
                    <a class="menu-opener">Services <i class="fa fa-angle-down"></i></a>
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
                                <h3><a class="no-link">{{ $cat['name'] }}</a></h3>
                              </div>
                              <ul>
                                @foreach(($cat['items'] ?? []) as $item)
                                @php $link = trim($item['link'] ?? ''); @endphp
                                <li>
                                  @if($link !== '' && $link !== '#')
                                    <a href="{{ $link }}">{{ $item['title'] ?? '' }}</a>
                                  @else
                                    {{-- No page behind it yet, so it is shown but not clickable. --}}
                                    <a class="no-link">{{ $item['title'] ?? '' }}</a>
                                  @endif
                                </li>
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
                    <a class="menu-opener">Public Notice <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu mega-menu row mega-menu-column-4 scrollbar" id="style-3">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            @php
                              // Match the approved design: a category that has sub categories puts
                              // them in its own column, and its plain links spill into a second,
                              // heading-less column beside it.
                              $menuColumns = [];
                              foreach (($noticeMenu ?? []) as $cat) {
                                  $subCats = $cat->children->filter(fn ($c) => $c->children->count())->values();
                                  $links   = $cat->children->filter(fn ($c) => !$c->children->count())->values();

                                  if ($subCats->count()) {
                                      $menuColumns[] = ['category' => $cat, 'items' => $subCats, 'heading' => true];
                                      if ($links->count()) {
                                          $menuColumns[] = ['category' => $cat, 'items' => $links, 'heading' => false];
                                      }
                                  } else {
                                      $menuColumns[] = ['category' => $cat, 'items' => $links, 'heading' => true];
                                  }
                              }
                            @endphp

                            @foreach($menuColumns as $col)
                            @php $cat = $col['category']; $hasFlyout = $col['items']->contains(fn ($i) => $i->children->count()); @endphp
                            <div class="col-md-2 list-item {{ $loop->last ? '' : 'border-right-one' }}">
                              @if($col['heading'])
                              <div class="mega-main-heading">
                                @if($cat->icon)
                                <div class="icon"><img src="{{ asset('public-notice/icons/'.$cat->icon) }}" alt="icon"></div>
                                @endif
                                <h3>
                                  @if($cat->url)
                                    <a href="{{ $cat->url }}">{{ $cat->name }}</a>
                                  @else
                                    <a class="no-link">{{ $cat->name }}</a>
                                  @endif
                                </h3>
                              </div>
                              @else
                              <div class="public-notices-mt-custom-sec"></div>
                              @endif

                              <ul @if($hasFlyout) class="sebi-compliance-main-menu-custom-sec" @endif>
                                @foreach($col['items'] as $item)
                                @php $hasChildren = $item->children->count() > 0; @endphp
                                <li @if($hasChildren) class="has-subsub" @endif>
                                  @if($hasChildren)
                                    {{-- Opens its own list rather than going anywhere, so it carries
                                         no address and never puts a "#" in the address bar. --}}
                                    <a class="subsub-toggle" role="button" aria-expanded="false">{{ $item->name }}<i class="fa fa-angle-down subsub-caret" aria-hidden="true"></i></a>
                                  @elseif($item->url)
                                    <a href="{{ $item->url }}" @if(in_array($item->link_type, ['pdf', 'url'], true)) target="_blank" rel="noopener noreferrer" @endif>{{ $item->name }}</a>
                                  @else
                                    <a class="no-link">{{ $item->name }}</a>
                                  @endif
                                  @if($hasChildren)
                                  <div class="sebi-compliance-subsub-menu-custom-sec">
                                    <ul>
                                      @foreach($item->children as $sub)
                                      <li>
                                        @if($sub->url)
                                          <a href="{{ $sub->url }}" @if(in_array($sub->link_type, ['pdf', 'url'], true)) target="_blank" rel="noopener noreferrer" @endif><i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $sub->name }}</a>
                                        @else
                                          <a class="no-link"><i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $sub->name }}</a>
                                        @endif
                                      </li>
                                      @endforeach
                                    </ul>
                                  </div>
                                  @endif
                                </li>
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
                    <a class="menu-opener">Grievance   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="{{ route('frontend.grievance_sebi') }}">For Services Regulated By SEBI</a></li>
                        <li><a href="{{ route('frontend.grievance_non_sebi') }}">For Services Not Regulated By SEBI</a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a class="menu-opener">Newsletter <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="{{ route('frontend.articles') }}">Articles</a></li>
                        <li><a href="{{ route('frontend.news_media') }}">News & Media </a></li>
                      </ul>
                    </div>
                  </li>
                  <li class="menu-item-has-children">
                    <a href="{{ route('frontend.careers') }}">Careers   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a href="{{ route('frontend.careers') }}#life-at-catalyst">Life at Catalyst</a></li>
                        <li><a href="{{ route('frontend.careers') }}#current-openings">Current Openings </a></li>
                      </ul>
                    </div>
                  </li>

                  <!-- <li class="menu-item-has-children">
                    <a class="menu-opener">Accessibility   <i class="fa fa-angle-down"></i></a>
                    <div class="sub-menu single-column-menu">
                      <ul>
                        <li><a class="no-link">Accessibility Statement</a></li>
                        <li><a class="no-link">Accessibility Tools</a></li>
                      </ul>
                    </div>
                  </li> -->
                  

                  {{-- A plain link, no drop-down: it goes straight to the Contact page,
                       which still carries all of its sections. --}}
                  <li class="right-menu">
                    <a href="{{ route('frontend.contact') }}">Contact</a>
                  </li>
                  <!-- <li>
                    <a class="no-link">Announcements</a>
                  </li> -->
                </ul>
              </nav>
            </div><!-- menu end here -->
            <div class="header-item header-right-item item-right">
              <ul class="nav-icon">
                <li class="hvr-icon-push nav-search">
                  <a href="javascript:void(0)" class="nav-icon-item icon-search" id="site-search-toggle"
                     aria-label="Search the website" aria-expanded="false">
                    <img src="{{ asset('frontend/assets/images/icons/search.svg')}}" class="hvr-icon">
                  </a>


                  <a class="btn-default" href="{{ optional($siteLinks)->get_started_link ?: route('frontend.contact') }}">{{ optional($siteLinks)->get_started_text ?: 'Get Started' }}</a>
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
