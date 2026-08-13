<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')
    @include('components.backend.sidebar')

     <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>Contact Page Content</h4></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('contact.index') }}">Contact Us</a></li>
                    <li class="breadcrumb-item active">Page Content</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>

          <div class="container-fluid">
            <form class="needs-validation custom-input" novalidate action="{{ route('contact.content.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-header"><h4>1. Banner</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label">Title</label>
                            <input class="form-control" type="text" name="banner_title" value="{{ old('banner_title', $content->banner_title ?? 'Contact') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Breadcrumb Parent</label>
                            <input class="form-control" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $content->banner_breadcrumb_parent ?? 'Contact') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Background Image</label>
                            <input class="form-control single-image-input" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($content && $content->banner_background_image)<img src="{{ asset('contact-media/banner/'.$content->banner_background_image) }}" style="max-height:80px;">@endif</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4>2. Contact Information</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-12">
                            <label class="form-label">Section Heading</label>
                            <input class="form-control" type="text" name="info_heading" value="{{ old('info_heading', $content->info_heading ?? 'Contact Information') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Phone</label>
                            <input class="form-control" type="text" name="phone" value="{{ old('phone', $content->phone ?? '') }}" placeholder="+91 (022) 4922 0555">
                            <label class="form-label mt-2">Phone Link</label>
                            <input class="form-control" type="text" name="phone_link" value="{{ old('phone_link', $content->phone_link ?? '') }}" placeholder="tel:+9102249220555">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="text" name="email" value="{{ old('email', $content->email ?? '') }}" placeholder="dt.mumbai@ctltrustee.com">
                            <label class="form-label mt-2">Email Link</label>
                            <input class="form-control" type="text" name="email_link" value="{{ old('email_link', $content->email_link ?? '') }}" placeholder="mailto:dt.mumbai@ctltrustee.com">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $content->address ?? '') }}</textarea>
                            <label class="form-label mt-2">Address Map Link</label>
                            <input class="form-control" type="text" name="address_link" value="{{ old('address_link', $content->address_link ?? '') }}" placeholder="https://maps...">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4>3. Enquiry Form</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label">Section Heading</label>
                            <input class="form-control" type="text" name="enquiry_heading" value="{{ old('enquiry_heading', $content->enquiry_heading ?? 'Enquiry Form') }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Form Heading</label>
                            <input class="form-control" type="text" name="form_heading" value="{{ old('form_heading', $content->form_heading ?? 'Get in Touch') }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Form Image</label>
                            <input class="form-control single-image-input" type="file" name="form_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($content && $content->form_image)<img src="{{ asset('contact-media/form/'.$content->form_image) }}" style="max-height:80px;">@endif</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><h4>4. Office Locations — Headings</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label">Section Heading</label>
                            <input class="form-control" type="text" name="office_heading" value="{{ old('office_heading', $content->office_heading ?? 'Office Locations') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Main Office Subtitle</label>
                            <input class="form-control" type="text" name="main_office_subtitle" value="{{ old('main_office_subtitle', $content->main_office_subtitle ?? 'Main Branch Office') }}">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Other Office Subtitle</label>
                            <input class="form-control" type="text" name="other_office_subtitle" value="{{ old('other_office_subtitle', $content->other_office_subtitle ?? 'Other Branch Office') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notice Bar Text <span class="text-secondary">(optional)</span></label>
                            <textarea class="form-control" name="notice_text" rows="2" placeholder="For forgot password of PF login...">{{ old('notice_text', $content->notice_text ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body d-flex justify-content-end gap-2">
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary px-4">Back</a>
                        <button class="btn btn-primary px-5" type="submit">Save Page Content</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
             @include('components.backend.footer')
      </div>
    </div>

        @include('components.backend.main-js')

</body>

</html>
