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
                <div class="col-6"><h4>Contact Us</h4></div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg></a></li>
                    <li class="breadcrumb-item active">Contact Us</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>

          <div class="container-fluid">

            {{-- ===== PAGE CONTENT (banner + info + enquiry + headings) ===== --}}
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
                        <div class="col-12">
                            <label class="form-label">Form Image</label>
                            <input class="form-control single-image-input" type="file" name="form_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($content && $content->form_image)<img src="{{ asset('contact-media/form/'.$content->form_image) }}" style="max-height:80px;">@endif</div>
                        </div>

                        {{-- The two dropdowns on the enquiry form, kept side by side
                             in their own row so they always line up. One option per line. --}}
                        <div class="col-12">
                          <div class="row g-4">
                        <div class="col-lg-6">
                            <label class="form-label">"Services" Dropdown Options</label>
                            <textarea class="form-control" name="services_options" rows="6"
                                      placeholder="Debenture Trustee Services&#10;Security Trustee&#10;Escrow Services">{{ old('services_options', $content->services_options ?? '') }}</textarea>
                            <small class="text-secondary d-block mt-1">
                                <i class="fa fa-info-circle"></i>
                                One option per line. These fill the <b>Select Services</b> dropdown on the contact form.
                                @if($content && count($content->optionList('services_options')))
                                    <span class="badge badge-light-success ms-1">{{ count($content->optionList('services_options')) }} option(s) live</span>
                                @else
                                    <span class="badge badge-light-danger ms-1">Empty - the dropdown has no choices</span>
                                @endif
                            </small>
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">"Location" Dropdown Options</label>
                            <textarea class="form-control" name="location_options" rows="6"
                                      placeholder="Mumbai&#10;Delhi&#10;Pune&#10;Bengaluru">{{ old('location_options', $content->location_options ?? '') }}</textarea>
                            <small class="text-secondary d-block mt-1">
                                <i class="fa fa-info-circle"></i>
                                One option per line. These fill the <b>Select Location</b> dropdown on the contact form.
                                @if($content && count($content->optionList('location_options')))
                                    <span class="badge badge-light-success ms-1">{{ count($content->optionList('location_options')) }} option(s) live</span>
                                @else
                                    <span class="badge badge-light-danger ms-1">Empty - the dropdown has no choices</span>
                                @endif
                            </small>
                        </div>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="row g-4">
                            <div class="col-lg-6">
                              <label class="form-label">Send New Enquiries To</label>
                              <input class="form-control" type="email" name="notify_email" value="{{ old('notify_email', $content->notify_email ?? '') }}" placeholder="e.g. dt.mumbai@ctltrustee.com">
                              <small class="text-secondary d-block mt-1">
                                <i class="fa fa-info-circle"></i>
                                Every enquiry is emailed here. The enquirer always gets an acknowledgement
                                at their own address. Leave blank to stop the team notification.
                              </small>
                            </div>
                            <div class="col-lg-6">
                              <label class="form-label">Copy To (CC)</label>
                              <input class="form-control" type="text" name="notify_cc" value="{{ old('notify_cc', $content->notify_cc ?? '') }}" placeholder="e.g. smrita@matrixbricks.com">
                              <small class="text-secondary d-block mt-1">
                                <i class="fa fa-info-circle"></i>
                                Anyone here is copied on the team notification. Separate several addresses
                                with commas. The enquirer never sees these addresses.
                              </small>
                            </div>
                          </div>
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
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary px-5" type="submit">Save Page Content</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- ===== OFFICES (section-wise) ===== --}}
            <div class="card">
              <div class="card-body">
                <h4 class="mb-4">Office Locations</h4>
                @php $byType = $offices->groupBy('type'); @endphp
                @foreach (\App\Models\ContactOffice::TYPES as $typeKey => $typeLabel)
                    @php $items = $byType->get($typeKey, collect()); $isMain = $typeKey === 'main'; @endphp
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fa fa-building-o text-primary"></i> {{ $typeLabel }}
                                <span class="badge badge-light-primary ms-2">{{ $items->count() }}</span></h5>
                            <a href="{{ route('contact.create', ['type' => $typeKey]) }}" class="btn btn-primary btn-sm radius-30">+ Add office</a>
                        </div>
                        <div class="table-responsive custom-scrollbar">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:45px;">#</th>
                                    <th style="width:70px;">Order</th>
                                    <th style="width:140px;">City</th>
                                    @if($isMain)<th style="width:140px;">Role</th>@else<th style="width:90px;">Tag</th>@endif
                                    <th>Address</th>
                                    <th style="width:180px;">Contact / Email</th>
                                    <th style="width:100px;">Status</th>
                                    <th style="width:120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $key => $o)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $o->sort_order }}</td>
                                        <td><b>{{ $o->city }}</b></td>
                                        @if($isMain)<td>{{ $o->role ?: '—' }}</td>@else<td>{{ $o->tag ?: '—' }}</td>@endif
                                        <td><small>{{ \Illuminate\Support\Str::limit($o->address, 90) }}</small></td>
                                        <td><small>@if($o->contact){{ $o->contact }}<br>@endif{{ $o->email }}</small></td>
                                        <td>@if($o->status)<span class="badge badge-light-success">Shown</span>@else<span class="badge badge-light-danger">Hidden</span>@endif</td>
                                        <td>
                                            <a href="{{ route('contact.edit', $o->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('contact.destroy', $o->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this office?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted">No offices in this group yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
             @include('components.backend.footer')
      </div>
    </div>

        @include('components.backend.main-js')

</body>

</html>
