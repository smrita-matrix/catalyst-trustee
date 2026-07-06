 <footer>
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-4 col-lg-4">
                <div class="footer-item">
                  <div class="footer-logo">
                    <div class="footer-about">
                      <img src="{{ $footer?->logo ? asset('home/footer/' . $footer->logo) : asset('frontend/assets/images/home/catalyst-logo.webp') }}" class="img-responsive" alt="Catalyst Trustee logo">
                      <p>{{ $footer?->description }}</p>
                    </div>
                    <ul class="social-list">
                      @foreach ($footer?->social_links ?? [] as $s)
                      <li class="hvr-icon-drop">
                        <a href="{{ ($s['url'] ?? '') ?: '#' }}" class="hvr-icon" target="_blank">
                        <i class="{{ $s['icon'] ?? '' }}"></i>
                        </a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-sm-4 col-lg-4">
                <div class="footer-item">
                  <div class="footer-service">
                    <h3>Quick Links</h3>
                    <ul>
                      <li><a href="#">Home </a></li>
                      <li><a href="#">About Us</a></li> 
                      <li><a href="#">Careers </a></li>
                      <li><a href="#">Contact Us </a></li>
                      <li><a href="#">Privacy Policy </a></li>
                      <li><a href="#">Terms & Conditions</a></li>
                      <li><a href="#">Disclaimer </a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-sm-4 col-lg-4">
                <div class="footer-item">
                  <div class="footer-touch">
                    <ul>
                      <li>
                        <h4>Phone</h4>
                        <a href="tel:{{ preg_replace('/\s+/', '', $footer?->phone ?? '') }}">{{ $footer?->phone }}</a>
                      </li>
                      <li>
                        <h4>Email</h4>
                        <a href="mailto:{{ $footer?->email }}">{{ $footer?->email }}</a>
                      </li>
                      <li>
                        <h4>Address</h4>
                        <p><a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($footer?->address ?? '') }}" target="_blank" rel="noopener">{{ $footer?->address }}</a></p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="footer-services-wrap">
              <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h6><a href="#">Sebi Regulated Services</a></h6>
                  <ul>
                    <li><a href="#">Debenture Trustee Services (Listed)</a></li> 
                    <li><a href="#">Securatization (Listed PTC </a></li> 
                    <li><a href="#">Alternative Investment Funds  </a></li> 
                  </ul>


                  <h6><a href="#">Newsletter / Insights</a></h6>
                  <ul>
                    <li><a href="#">Articles &amp; Insights</a></li>
                    <li><a href="#">News &amp; Media </a></li>
                  </ul>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h6><a href="#">Non Sebi Regulated Services</a></h6>
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

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h6><a href="#">GIFT City Services</a></h6>
                  <ul>
                    <li><a href="#">Introduction</a></li>
                    <li><a href="#">AIF in GIFT City</a></li> 
                    <li><a href="#">Facility Agent </a></li>
                    <li><a href="#">Bond/Debenture Trustee </a></li>
                    <li><a href="#">Corporate Services </a></li>
                    <li><a href="#">Family Investment Funds </a></li>
                    <li><a href="#">Grievance Redressal – Contact us</a></li> 
                  </ul>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h6><a href="#">Public Notice</a></h6>
                  <ul>
                    <li><a href="#">Notices &amp; Announcements </a></li>
                    <li><a href="#">Regulatory Disclosures </a></li>
                  </ul>


                  <h6><a href="#">Grievance</a></h6>
                  <ul>
                    <li><a href="#">Investor Grievance</a></li>
                    <li><a href="#">Contact for Support </a></li>
                  </ul>


                  <h6><a href="#">Transactions / Case Studies</a></h6>
                  <ul>
                    <li><a href="#">Key Transactions</a></li>
                  </ul>
                </div>
              </div>
            </div> -->
          </div>
        </footer>