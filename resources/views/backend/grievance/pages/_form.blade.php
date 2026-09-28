@csrf
@if ($page->exists) @method('PUT') @endif

<div class="card">
  <div class="card-header">
    <h4>The page itself</h4>
    <p class="f-m-light mt-1 mb-0">The title is what shows in the Grievance menu.</p>
  </div>
  <div class="card-body row g-4">
    <div class="col-lg-6">
      <label class="form-label" for="title">Title <span class="txt-danger">*</span></label>
      <input class="form-control" id="title" type="text" name="title" required
             value="{{ old('title', $page->title) }}" placeholder="e.g. For Services in GIFT City">
      <div class="invalid-feedback">Please give the page a title.</div>
    </div>

    <div class="col-lg-6">
      <label class="form-label" for="slug">Address <span class="text-secondary">(optional)</span></label>
      <div class="input-group">
        <span class="input-group-text">/grievance/</span>
        <input class="form-control" id="slug" type="text" name="slug"
               value="{{ old('slug', $page->slug) }}" placeholder="made from the title">
      </div>
      <small class="d-block text-secondary mt-1">Leave it be once the page is live — changing it breaks any link already shared.</small>
    </div>

    <div class="col-lg-4">
      <label class="form-label" for="sort_order">Order in the menu</label>
      <input class="form-control" id="sort_order" type="number" name="sort_order"
             value="{{ old('sort_order', $page->sort_order ?? 0) }}" placeholder="0">
      <small class="d-block text-secondary mt-1">Lower numbers come first.</small>
    </div>

    <div class="col-lg-4">
      <label class="form-label" for="link_target">The menu entry opens</label>
      <select class="form-select" id="link_target" name="link_target">
        @foreach (\App\Models\GrievancePage::LINK_TARGETS as $key => $label)
        <option value="{{ $key }}" {{ old('link_target', $page->link_target ?? 'page') === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
      <small class="d-block text-secondary mt-1">Choose the document to send people straight to the PDF, with no page in between.</small>
    </div>

    <div class="col-lg-4">
      <label class="form-label" for="form_type">Form on the page</label>
      <select class="form-select" id="form_type" name="form_type">
        @foreach (\App\Models\GrievancePage::FORMS as $key => $label)
        <option value="{{ $key }}" {{ old('form_type', $page->form_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-lg-4">
      <label class="form-label d-block">Status</label>
      <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
               {{ old('status', $page->status ?? 1) ? 'checked' : '' }}>
        <label class="form-check-label" for="status">Show in the menu</label>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h4>Banner</h4>
    <p class="f-m-light mt-1 mb-0">The strip across the top of the page.</p>
  </div>
  <div class="card-body row g-4">
    <div class="col-lg-4">
      <label class="form-label" for="banner_title">Banner title</label>
      <input class="form-control" id="banner_title" type="text" name="banner_title"
             value="{{ old('banner_title', $page->banner_title) }}" placeholder="e.g. Investor Grievances">
    </div>
    <div class="col-lg-4">
      <label class="form-label" for="breadcrumb_child">Breadcrumb</label>
      <input class="form-control" id="breadcrumb_child" type="text" name="breadcrumb_child"
             value="{{ old('breadcrumb_child', $page->breadcrumb_child) }}" placeholder="same as the title">
    </div>
    <div class="col-lg-4">
      <label class="form-label" for="banner_image">Background picture</label>
      <input class="form-control single-image-input" id="banner_image" type="file" name="banner_image" accept=".jpg,.jpeg,.png,.webp">
      <div class="img-preview mt-2">
        @if ($page->banner_image)<img src="{{ asset('grievance/banner/'.$page->banner_image) }}" alt="banner">@endif
      </div>
      <small class="d-block text-secondary mt-1">Leave empty to keep the current one.</small>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h4>Wording</h4>
    <p class="f-m-light mt-1 mb-0">The heading, the line under it, and anything the page needs to say in full.</p>
  </div>
  <div class="card-body row g-4">
    <div class="col-lg-6">
      <label class="form-label" for="heading">Heading</label>
      <input class="form-control" id="heading" type="text" name="heading"
             value="{{ old('heading', $page->heading) }}" placeholder="same as the title">
    </div>
    <div class="col-lg-6">
      <label class="form-label" for="intro">Line under the heading</label>
      <input class="form-control" id="intro" type="text" name="intro"
             value="{{ old('intro', $page->intro) }}" placeholder="e.g. Write to us at:">
    </div>
    <div class="col-12">
      <label class="form-label" for="editor">Page content <span class="text-secondary">(for a page that is simply something to read)</span></label>
      <textarea class="form-control" id="editor" name="body">{{ old('body', $page->body) }}</textarea>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h4>Who to write to</h4>
      <p class="f-m-light mt-1 mb-0">Shown down the side of the page. Leave empty if there is nobody to list.</p>
    </div>
    <button type="button" id="btn-add-contact" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
  </div>
  <div class="card-body">
    <div class="table-responsive custom-scrollbar">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:55px;">#</th>
            <th style="width:300px;">Label</th>
            <th>Detail</th>
            <th style="width:170px;">Written as</th>
            <th style="width:60px;"></th>
          </tr>
        </thead>
        <tbody id="contacts-wrap">
          @php
            $rows = old('contact_label')
                ? array_map(fn ($l, $v, $k) => ['label' => $l, 'value' => $v, 'kind' => $k],
                            old('contact_label'), old('contact_value', []), old('contact_kind', []))
                : ($page->contacts ?: [['label' => '', 'value' => '', 'kind' => 'text']]);
          @endphp
          @foreach ($rows as $row)
          <tr class="contact-item">
            <td class="contact-index"></td>
            <td><input class="form-control" type="text" name="contact_label[]" value="{{ $row['label'] ?? '' }}" placeholder="e.g. Complaint Redressal Officer (CRO)"></td>
            <td><textarea class="form-control" name="contact_value[]" rows="2" placeholder="e.g. Mr. Nikhil Shahdadpuri">{{ $row['value'] ?? '' }}</textarea></td>
            <td>
              <select class="form-select" name="contact_kind[]">
                @foreach (\App\Models\GrievancePage::CONTACT_KINDS as $key => $label)
                <option value="{{ $key }}" {{ ($row['kind'] ?? 'text') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
            </td>
            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-contact"><i class="fa fa-trash"></i></button></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <small class="text-secondary"><i class="fa fa-info-circle"></i> An email address or a phone number becomes a link people can tap.</small>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h4>Document and note</h4>
    <p class="f-m-light mt-1 mb-0">Something to open alongside the page, and any small print under it.</p>
  </div>
  <div class="card-body row g-4">
    <div class="col-lg-4">
      <label class="form-label" for="document_file">PDF</label>
      <input class="form-control" id="document_file" type="file" name="document_file" accept=".pdf">
      @if ($page->document_file)
      <small class="d-block text-secondary mt-1">
        Currently <a href="{{ asset('grievance/documents/'.$page->document_file) }}" target="_blank" rel="noopener noreferrer">{{ $page->document_file }}</a>.
        Leave empty to keep it.
      </small>
      @endif
    </div>
    <div class="col-lg-4">
      <label class="form-label" for="document_label">Button wording</label>
      <input class="form-control" id="document_label" type="text" name="document_label"
             value="{{ old('document_label', $page->document_label) }}" placeholder="e.g. View Document">
    </div>
    <div class="col-lg-4">
      <label class="form-label" for="external_link">Or a link instead</label>
      <input class="form-control" id="external_link" type="text" name="external_link"
             value="{{ old('external_link', $page->external_link) }}" placeholder="https://...">
      <small class="d-block text-secondary mt-1">Used only when no PDF is attached.</small>
    </div>
    <div class="col-12">
      <label class="form-label" for="note">Note under the side panel</label>
      <textarea class="form-control" id="note" name="note" rows="2" placeholder="Any small print">{{ old('note', $page->note) }}</textarea>
    </div>
  </div>
</div>

<div class="d-flex justify-content-end gap-2 pb-5">
  <a href="{{ route('grievance-pages.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
  <button class="btn btn-primary px-4" type="submit">{{ $page->exists ? 'Update' : 'Submit' }}</button>
</div>
