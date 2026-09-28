{{-- The form for services SEBI regulates. --}}
<form action="{{ route('frontend.investor_grievance.sebi.store') }}" method="POST">
              @csrf
              <input type="hidden" name="_form" value="sebi">

              <div class="grievances-section">
                <h3 data-aos="fade-up" data-aos-duration="1000">Investor Grievance Form</h3>

                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Full Name <span>*</span></label>
                      <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required
                             pattern="[A-Za-z\s.'-]+" title="Letters only — no numbers." placeholder="e.g. Jane A. Smith">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Mobile Number <span>*</span></label>
                      <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" required
                             pattern="[0-9+\s-]{7,20}" title="Digits only, at least 7." placeholder="e.g. +91 98765 43210">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>ISIN Number <span>*</span></label>
                      <input type="text" name="isin" class="form-control" value="{{ old('isin') }}" required placeholder="e.g. INE001A01036">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Pan Number <span>*</span></label>
                      <input type="text" name="pan" class="form-control" value="{{ old('pan') }}" required
                             maxlength="10" placeholder="e.g. ABCDE1234F" style="text-transform:uppercase;">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Email ID <span>*</span></label>
                      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. name@example.com">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Name of Issuer <span>*</span></label>
                      <input type="text" name="issuer_name" class="form-control" value="{{ old('issuer_name') }}" required placeholder="e.g. Example Finance Limited">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Investment Details <span>*</span></label>
                      <textarea name="investment_details" class="form-control" rows="3" required placeholder="e.g. 100 debentures bought in March 2024, folio number 12345">{{ old('investment_details') }}</textarea>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Nature of Complaint <span>*</span></label>
                      <textarea name="nature_of_complaint" class="form-control" rows="4" maxlength="1000" required placeholder="Describe what went wrong, with any dates or reference numbers">{{ old('nature_of_complaint') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grievance-actions">
                <button type="submit" class="btn-default">Submit</button>
              </div>
            </form>
