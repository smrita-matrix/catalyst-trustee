{{-- Shared add/edit body for the Grievance page content. --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

@php
  $options = old('complaint_options', $content->complaint_options ?: ['']);
  $notes   = old('notes', $content->notes ?: ['']);
@endphp

<div class="row g-4">

  {{-- ---------------- Banner (kept at the top) ---------------- --}}
  <div class="col-12">
    <div class="card border mt-2">
      <div class="card-header py-2"><b>Page Banner</b></div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-lg-4">
            <label class="form-label">Banner Heading</label>
            <input class="form-control" type="text" name="banner_title" value="{{ old('banner_title', $content->banner_title) }}" placeholder="e.g. Investor Grievances">
          </div>
          <div class="col-lg-4">
            <label class="form-label">Breadcrumb Label</label>
            <input class="form-control" type="text" name="breadcrumb_child" value="{{ old('breadcrumb_child', $content->breadcrumb_child) }}" placeholder="defaults to the banner heading">
          </div>
          <div class="col-lg-4">
            <label class="form-label">Banner Background Image</label>
            <input class="form-control" type="file" name="banner_image" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Recommended <b>1500 × 976 px</b> — WebP, JPG or PNG, max 4 MB. Cropped to fill from the centre.
            </small>
            @if($content->banner_image)
              <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
                <img src="{{ asset('grievance/banner/'.$content->banner_image) }}" alt="banner" style="max-height:110px; border-radius:6px; display:block;">
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ---------------- Page copy ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Investor Grievance — Page Text</b>
        <small class="text-secondary d-block">Only the wording is editable. The form fields themselves are fixed.</small>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-12">
            <label class="form-label">Note Above the Form</label>
            <textarea class="form-control" name="intro_text" rows="2" placeholder="e.g. (This portal is only for investors in Debenture transactions…)">{{ old('intro_text', $content->intro_text) }}</textarea>
          </div>
          <div class="col-lg-6">
            <label class="form-label">First Section Heading</label>
            <input class="form-control" type="text" name="holder_heading" value="{{ old('holder_heading', $content->holder_heading) }}" placeholder="Investor/Debenture Holder Details">
          </div>
          <div class="col-lg-6">
            <label class="form-label">Second Section Heading</label>
            <input class="form-control" type="text" name="instrument_heading" value="{{ old('instrument_heading', $content->instrument_heading) }}" placeholder="Instrument Details &amp; Grievance">
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ---------------- Email notifications ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Email Notifications</b>
        <small class="text-secondary d-block">
          Each form below names its own grievance officer, and submissions go to that officer.
          This address is the fallback when an officer's address is left blank.
        </small>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-lg-6">
            <label class="form-label">Fallback Address</label>
            <input class="form-control" type="email" name="notify_email" value="{{ old('notify_email', $content->notify_email) }}" placeholder="e.g. grievance@ctltrustee.com">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Used only when the form's own officer has no address set. The person who submitted
              always gets an acknowledgement at their own address.
            </small>
          </div>

          <div class="col-lg-6">
            <label class="form-label">Copy To (CC)</label>
            <input class="form-control" type="text" name="notify_cc" value="{{ old('notify_cc', $content->notify_cc) }}" placeholder="e.g. smrita@matrixbricks.com">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Anyone here is copied on the officer's notification. Separate several addresses with
              commas. The person who submitted never sees these addresses.
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- ---------------- The two grievance forms ---------------- --}}
  @foreach ([
      'sebi'     => ['For Services Regulated By SEBI', 'Compliance and Grievance Officer'],
      'non_sebi' => ['For Services Not Regulated By SEBI', 'Grievance Officer'],
  ] as $key => $labels)
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Tab &mdash; {{ $labels[0] }}</b>
        <small class="text-secondary d-block">The wording above this form and the officer named beneath it.</small>
      </div>
      <div class="card-body">
        <div class="row g-4">

          <div class="col-lg-6">
            <label class="form-label">Tab Label</label>
            <input class="form-control" type="text" name="{{ $key }}_heading"
                   value="{{ old($key.'_heading', $content->{$key.'_heading'}) }}" placeholder="{{ $labels[0] }}">
          </div>

          <div class="col-lg-6">
            <label class="form-label">{{ $labels[1] }} &mdash; Name</label>
            <input class="form-control" type="text" name="{{ $key }}_officer_name"
                   value="{{ old($key.'_officer_name', $content->{$key.'_officer_name'}) }}" placeholder="e.g. Ms. Kalyani Pandey">
          </div>

          <div class="col-12">
            <label class="form-label">Note Above This Form</label>
            <textarea class="form-control" name="{{ $key }}_intro" rows="2"
                      placeholder="Optional. Shown above the form.">{{ old($key.'_intro', $content->{$key.'_intro'}) }}</textarea>
          </div>

          <div class="col-lg-6">
            <label class="form-label">Officer Phone</label>
            <input class="form-control" type="text" name="{{ $key }}_officer_phone"
                   value="{{ old($key.'_officer_phone', $content->{$key.'_officer_phone'}) }}" placeholder="e.g. +91 22 4922 0555">
          </div>

          <div class="col-lg-6">
            <label class="form-label">Officer Email</label>
            <input class="form-control" type="email" name="{{ $key }}_officer_email"
                   value="{{ old($key.'_officer_email', $content->{$key.'_officer_email'}) }}" placeholder="e.g. grievance@ctltrustee.com">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Grievances from this form are emailed here.
            </small>
          </div>

          @if($key === 'non_sebi')
          <div class="col-12">
            <label class="form-label">Note Below This Form</label>
            <textarea class="form-control" name="non_sebi_note" rows="3"
                      placeholder="e.g. None of the SEBI investor protection mechanisms shall be available...">{{ old('non_sebi_note', $content->non_sebi_note) }}</textarea>
          </div>
          @endif

        </div>
      </div>
    </div>
  </div>
  @endforeach

  {{-- ---------------- Complaint tick-boxes ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <b>Complaint Particulars (tick-box options)</b>
        <button type="button" class="btn btn-outline-primary btn-sm" data-add="options"><i class="fa fa-plus"></i> Add More</button>
      </div>
      <div class="card-body">
        <div id="options-list">
          @foreach ($options as $option)
          <div class="input-group mb-2 repeat-row">
            <input class="form-control" type="text" name="complaint_options[]" value="{{ $option }}" placeholder="e.g. Non-Receipt of Interest / Principal">
            <button type="button" class="btn btn-outline-danger btn-remove"><i class="fa fa-trash"></i></button>
          </div>
          @endforeach
        </div>
        <small class="text-secondary">These become the tick boxes on the form. Empty rows are ignored.</small>
      </div>
    </div>
  </div>

  {{-- ---------------- Footnotes ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <b>Notes Below the Form</b>
        <button type="button" class="btn btn-outline-primary btn-sm" data-add="notes"><i class="fa fa-plus"></i> Add More</button>
      </div>
      <div class="card-body">
        <div id="notes-list">
          @foreach ($notes as $note)
          <div class="input-group mb-2 repeat-row">
            <textarea class="form-control" name="notes[]" rows="2" placeholder="e.g. You can directly write to grievance@ctltrustee.com…">{{ $note }}</textarea>
            <button type="button" class="btn btn-outline-danger btn-remove"><i class="fa fa-trash"></i></button>
          </div>
          @endforeach
        </div>
        <small class="text-secondary">Basic HTML allowed, e.g. &lt;a href="https://www.scores.gov.in/" target="_blank"&gt;scores.gov.in&lt;/a&gt;</small>
      </div>
    </div>
  </div>

</div>

<script>
  // Simple repeaters for the tick-box options and the notes.
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-add]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var which = btn.getAttribute('data-add');
        var list  = document.getElementById(which + '-list');
        var row   = list.querySelector('.repeat-row').cloneNode(true);
        row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
        list.appendChild(row);
      });
    });

    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn-remove');
      if (!btn) return;
      var list = btn.closest('[id$="-list"]');
      if (list && list.querySelectorAll('.repeat-row').length > 1) {
        btn.closest('.repeat-row').remove();
      } else {
        btn.closest('.repeat-row').querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
      }
    });
  });
</script>
