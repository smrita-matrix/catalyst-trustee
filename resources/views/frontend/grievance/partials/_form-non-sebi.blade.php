{{-- The form for services SEBI does not regulate. --}}
<form action="{{ route('frontend.investor_grievance.store') }}" method="POST">
              @csrf
              <input type="hidden" name="_form" value="non_sebi">

              <div class="grievances-section">
                <h3 data-aos="fade-up" data-aos-duration="1000">{{ optional($content)->holder_heading ?: 'Investor/Debenture Holder Details' }}</h3>

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
                      <label>PAN <span>*</span></label>
                      <input type="text" name="pan" class="form-control" value="{{ old('pan') }}" required
                             maxlength="10" placeholder="e.g. ABCDE1234F" style="text-transform:uppercase;">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Email Address <span>*</span></label>
                      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. name@example.com">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Mobile/Contact Number</label>
                      <input type="tel" name="mobile" class="form-control" value="{{ old('mobile') }}" placeholder="e.g. +91 98765 43210">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Full Postal Address (as mentioned in the Debenture Application) <span>*</span></label>
                      <textarea name="address" class="form-control" rows="3" required placeholder="Flat / house number, street, area, city, state and PIN code">{{ old('address') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grievances-section">
                <h3 data-aos="fade-up" data-aos-duration="1000">{{ optional($content)->instrument_heading ?: 'Instrument Details & Grievance' }}</h3>

                <div class="row">
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Debenture Issuer Name <span>*</span></label>
                      <input type="text" name="issuer_name" class="form-control" value="{{ old('issuer_name') }}" required placeholder="e.g. Example Finance Limited">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>Debenture Series Name</label>
                      <input type="text" name="series_name" class="form-control" value="{{ old('series_name') }}" placeholder="e.g. Series A / Tranche II (leave blank if not applicable)">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>ISIN / Multiple ISIN <span>*</span></label>
                      <input type="text" name="isin" class="form-control" value="{{ old('isin') }}" required placeholder="e.g. INE001A01036">
                    </div>
                  </div>

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label>No of Bonds held <span>*</span></label>
                      <input type="number" name="bonds_held" class="form-control" value="{{ old('bonds_held') }}" min="1" required placeholder="e.g. 50">
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Complaints Particulars <span>*</span></label>
                      <div class="grievance-checks">
                        @foreach($options as $option)
                        <label class="grievance-check">
                          <input type="checkbox" name="complaint_types[]" value="{{ $option }}"
                                 {{ in_array($option, (array) old('complaint_types', []), true) ? 'checked' : '' }}>
                          <span>{{ $option }}</span>
                        </label>
                        @endforeach
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Details of Grievance / Complaint <span>*</span></label>
                      <textarea name="complaint_details" class="form-control" rows="4" maxlength="1000" required
                                placeholder="Describe your complaint, within 1000 characters">{{ old('complaint_details') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grievance-actions">
                <button type="submit" class="btn-default">Submit</button>
              </div>
            </form>
