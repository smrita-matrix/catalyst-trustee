{{-- Careers page content — banner first, then the page text. --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
  </div>
@endif

<div class="row g-4">

  {{-- ---------------- Banner (top) ---------------- --}}
  <div class="col-12">
    <div class="card border mt-2">
      <div class="card-header py-2"><b>Page Banner</b></div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-lg-4">
            <label class="form-label">Banner Heading</label>
            <input class="form-control" type="text" name="banner_title" value="{{ old('banner_title', $content->banner_title) }}" placeholder="e.g. Careers">
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
              Recommended <b>1500 &times; 976 px</b> — WebP, JPG or PNG, max 4 MB. Cropped to fill from the centre.
            </small>
            @if($content->banner_image)
              <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
                <img src="{{ asset('career-uploads/banner/'.$content->banner_image) }}" alt="banner" style="max-height:110px; border-radius:6px; display:block;">
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ---------------- Intro block ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Intro (above the job openings)</b></div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-12">
            <label class="form-label">Intro Heading</label>
            <textarea class="form-control" name="intro_heading" rows="2" placeholder="e.g. JOIN CATALYST TRUSTEESHIP LIMITED for all-around expertise…">{{ old('intro_heading', $content->intro_heading) }}</textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Intro Paragraph</label>
            <textarea class="form-control" name="intro_text" rows="5" placeholder="The paragraph shown under the heading">{{ old('intro_text', $content->intro_text) }}</textarea>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ---------------- Form headings ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Application Form Headings</b>
        <small class="text-secondary d-block">Only the headings are editable — the form fields themselves are fixed.</small>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-lg-6">
            <label class="form-label">Small Heading</label>
            <input class="form-control" type="text" name="form_sub_heading" value="{{ old('form_sub_heading', $content->form_sub_heading) }}" placeholder="e.g. Apply Now">
          </div>
          <div class="col-lg-6">
            <label class="form-label">Main Heading</label>
            <input class="form-control" type="text" name="form_heading" value="{{ old('form_heading', $content->form_heading) }}" placeholder="e.g. Submit Your Resume">
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ---------------- Email notifications ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Email Notifications</b></div>
      <div class="card-body">
        <div class="row g-4">
          <div class="col-lg-6">
            <label class="form-label">Send New Applications To</label>
            <input class="form-control" type="email" name="notify_email" value="{{ old('notify_email', $content->notify_email) }}" placeholder="e.g. hr@ctltrustee.com">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Every application is emailed here <b>with the candidate's resume attached</b>.
              Leave blank to stop the HR notification.
            </small>
          </div>
          <div class="col-lg-6">
            <label class="form-label">Copy To (CC)</label>
            <input class="form-control" type="text" name="notify_cc" value="{{ old('notify_cc', $content->notify_cc) }}" placeholder="e.g. smrita@matrixbricks.com">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Anyone here is copied on the HR notification. Separate several addresses with commas.
              The candidate never sees these addresses.
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
