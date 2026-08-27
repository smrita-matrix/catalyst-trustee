{{-- Add/edit form. $level decides which fields are relevant:
     1 = Category (menu column), 2 = Sub Category (fly-out heading), 3 = Page. --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

@php
  $selectedParent = old('parent_id', $category->parent_id ?? ($parentId ?? null));
  $selectedType   = old('link_type', $category->link_type ?: ($level === 2 ? 'none' : 'page'));
  $nameLabel      = [1 => 'Category Name', 2 => 'Sub Category Name', 3 => 'Page Name'][$level];
@endphp

<input type="hidden" name="level" value="{{ $level }}">

<div class="row g-4">

  @if($level === 3)
    {{-- Banner first, so the page's image and heading are the first thing set. --}}
    <div class="col-12 type-block" data-type="page">
      <div class="card border mt-2">
        <div class="card-header py-2"><b>Page Banner</b></div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-lg-6">
              <label class="form-label">Banner Heading</label>
              <input type="text" name="banner_title" class="form-control" value="{{ old('banner_title', $category->banner_title) }}" placeholder="defaults to the page heading">
            </div>
            <div class="col-lg-6">
              <label class="form-label">Banner Background Image</label>
              <input type="file" name="banner_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
              <small class="text-secondary d-block mt-1">
                <i class="fa fa-info-circle"></i>
                Recommended <b>1500 × 976 px</b> — WebP, JPG or PNG, max 4 MB.
                The image is cropped to fill the banner from the centre, so keep the subject in the middle.
                Leave empty to use the shared Public Notice banner.
              </small>
              @php
                $shared = \App\Models\NoticeBannerDetails::whereNull('deleted_at')->latest('id')->first();
                $previewImage = $category->banner_image ?: optional($shared)->background_image;
              @endphp
              @if($previewImage)
                <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
                  <img src="{{ asset('public-notice/banner/'.$previewImage) }}" alt="banner preview"
                       style="max-height:120px; max-width:100%; border-radius:6px; display:block;">
                  <small class="text-secondary d-block mt-1">
                    @if($category->banner_image)
                      This page's own banner.
                    @else
                      Currently using the shared Public Notice banner.
                    @endif
                  </small>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="col-lg-6">
    <label class="form-label">{{ $nameLabel }} <span class="txt-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required
           placeholder="Exactly as it should read in the menu">
  </div>

  @if($level === 1)
    {{-- A category is always top level. --}}
    <input type="hidden" name="parent_id" value="">
    <input type="hidden" name="link_type" value="none">

    <div class="col-lg-6">
      <label class="form-label">Menu Icon</label>
      <input type="file" name="icon" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">
      <small class="text-secondary d-block mt-1">
        <i class="fa fa-info-circle"></i>
        Shown next to this heading in the menu. Recommended <b>512 × 512 px</b> square —
        WebP, PNG or SVG with a transparent background, max 2 MB.
      </small>
      @if($category->icon)
        <div class="mt-2"><img src="{{ asset('public-notice/icons/'.$category->icon) }}" alt="icon" style="height:38px;"></div>
      @endif
    </div>
  @else
    <div class="col-lg-6">
      <label class="form-label">
        {{ $level === 2 ? 'Inside Category' : 'Inside Category / Sub Category' }} <span class="txt-danger">*</span>
      </label>
      <select name="parent_id" class="form-control" required>
        <option value="">— Choose —</option>
        @foreach ($parents as $parent)
          <option value="{{ $parent->id }}" {{ (string) $selectedParent === (string) $parent->id ? 'selected' : '' }}>
            {{ $parent->parent_id ? '— ' : '' }}{{ $parent->name }}
          </option>
        @endforeach
      </select>
      @if($level === 3)
        <small class="text-secondary">Indented options are sub categories — pick one only if this page belongs in its fly-out.</small>
      @endif
    </div>
  @endif

  @if($level === 2)
    {{-- A sub category is a heading; it never has a page of its own. --}}
    <input type="hidden" name="link_type" value="none">
  @endif

  @if($level === 3)
    <div class="col-lg-6">
      <label class="form-label">This Page Opens <span class="txt-danger">*</span></label>
      <select name="link_type" class="form-control" id="link-type-select">
        @foreach (\App\Models\NoticeCategory::LINK_TYPES as $key => $label)
          @continue($key === 'none')
          <option value="{{ $key }}" {{ $selectedType === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>
  @endif

  <div class="col-lg-3">
    <label class="form-label">Display Order</label>
    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
    <small class="text-secondary">Lower numbers come first.</small>
  </div>

  <div class="col-lg-3">
    <label class="form-label d-block">Status</label>
    <div class="form-check form-switch mt-2">
      <input type="checkbox" name="status" value="1" class="form-check-input" id="status-toggle"
        {{ old('status', $category->status ?? 1) ? 'checked' : '' }}>
      <label class="form-check-label" for="status-toggle">Show in menu</label>
    </div>
  </div>

  @if($level === 3)
    {{-- ---------- Page (link_type = page) ---------- --}}
    <div class="col-12 type-block" data-type="page">
      <div class="card border mt-4">
        <div class="card-header py-2"><b>Page Design</b></div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-lg-6">
              <label class="form-label">Design</label>
              <select name="layout" class="form-control">
                @foreach (\App\Models\NoticeCategory::LAYOUTS as $key => $label)
                  <option value="{{ $key }}" {{ old('layout', $category->layout) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
              <small class="text-secondary d-block mt-1">
                How the documents on this page are arranged.
                <a href="{{ route('notice-layout-guide') }}" target="_blank" rel="noopener"><b>See all layouts</b></a>
                to compare them before choosing.
              </small>
            </div>

            <div class="col-lg-6">
              <label class="form-label">Page Heading</label>
              <input type="text" name="page_title" class="form-control" value="{{ old('page_title', $category->page_title) }}" placeholder="defaults to the page name">
            </div>

            <div class="col-lg-6">
              <label class="form-label">Notice Box Heading</label>
              <input type="text" name="alert_heading" class="form-control" value="{{ old('alert_heading', $category->alert_heading) }}" placeholder="e.g. Attention Investors!">
            </div>

            <div class="col-12">
              <label class="form-label">Notice Box Text</label>
              <textarea name="alert_text" class="form-control" rows="2" placeholder="Leave both boxes blank to hide it.">{{ old('alert_text', $category->alert_text) }}</textarea>
              <small class="text-secondary">Basic HTML allowed, e.g. &lt;a href="mailto:someone@ctltrustee.com"&gt;someone@ctltrustee.com&lt;/a&gt;</small>
            </div>
          </div>
        </div>
      </div>

      <div class="card border mt-4">
        <div class="card-header py-2 d-flex justify-content-between align-items-center">
          <b>Page Content — Documents</b>
          <button type="button" id="btn-add-doc" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
        </div>
        <div class="card-body">
          <div class="table-responsive custom-scrollbar">
            <table class="table table-bordered align-middle" id="docs-table">
              <thead class="table-light">
                <tr>
                  <th style="width:45px;">#</th>
                  <th style="width:150px;">Group</th>
                  <th style="width:120px;">Date</th>
                  <th style="width:230px;">Title <span class="txt-danger">*</span></th>
                  <th>Description</th>
                  <th style="width:240px;">PDF / Link</th>
                  <th style="width:50px;"></th>
                </tr>
              </thead>
              <tbody id="docs-tbody">
                @foreach (($documents ?? collect()) as $i => $doc)
                <tr class="doc-row">
                  <td class="doc-index">{{ $i + 1 }}</td>
                  <td>
                    <input type="hidden" name="doc_id[]" value="{{ $doc->id }}">
                    <input class="form-control" type="text" name="doc_group[]" value="{{ $doc->period }}" placeholder="e.g. FY 2025-26">
                  </td>
                  <td><input class="form-control" type="text" name="doc_date[]" value="{{ $doc->notice_date }}" placeholder="16.01.2025"></td>
                  <td><input class="form-control" type="text" name="doc_title[]" value="{{ $doc->title }}" placeholder="Document name"></td>
                  <td><textarea class="form-control" name="doc_description[]" rows="2" placeholder="Optional description">{{ $doc->description }}</textarea></td>
                  <td>
                    <input class="form-control mb-1" type="file" name="doc_file[]" accept=".pdf,.jpg,.jpeg,.png,.webp">
                    @if($doc->document_file)
                      <small class="d-block mb-1"><a href="{{ asset('public-notice/documents/'.$doc->document_file) }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-file-pdf-o"></i> Current file</a></small>
                    @endif
                    <input class="form-control" type="text" name="doc_link[]" value="{{ $doc->document_file ? '' : $doc->document_link }}" placeholder="or paste a link">
                  </td>
                  <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-doc" title="Remove"><i class="fa fa-trash"></i></button></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <small class="text-secondary">
            <i class="fa fa-info-circle"></i>
            These are the documents shown on this page, in this order. Only <b>Title</b> is required —
            upload a PDF or paste a link. Rows deleted here are removed from the website when you save.
            <b>Group</b> is the heading documents are stacked under (e.g. <i>FY 2025-26</i>) for the designs that use it.
          </small>
        </div>
      </div>

    </div>

    {{-- ---------- PDF ---------- --}}
    <div class="col-12 type-block" data-type="pdf">
      <label class="form-label">PDF Document</label>
      <input type="file" name="document_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
      <small class="text-secondary d-block mt-1">
        <i class="fa fa-info-circle"></i>
        PDF, JPG, PNG or WebP, max 20 MB. Clicking this menu item opens the file in a new tab.
      </small>
      @if($category->document_file)
        <small class="d-block mt-1">Current:
          <a href="{{ asset('public-notice/documents/'.$category->document_file) }}" target="_blank" rel="noopener noreferrer">{{ $category->document_file }}</a>
        </small>
      @endif
    </div>

    {{-- ---------- External link ---------- --}}
    <div class="col-12 type-block" data-type="url">
      <label class="form-label">External Link</label>
      <input type="url" name="external_link" class="form-control" value="{{ old('external_link', $category->external_link) }}" placeholder="https://...">
    </div>
  @endif

</div>

@if($level === 3)
<script>
  // Only show the block that matches what this page opens.
  (function () {
    var typeSelect = document.getElementById('link-type-select');
    if (!typeSelect) return;

    function refresh() {
      document.querySelectorAll('.type-block').forEach(function (block) {
        block.style.display = block.getAttribute('data-type') === typeSelect.value ? '' : 'none';
      });
    }

    typeSelect.addEventListener('change', refresh);
    refresh();
  })();

  // Documents table: add / remove rows and keep the numbering right.
  (function () {
    var tbody  = document.getElementById('docs-tbody');
    var addBtn = document.getElementById('btn-add-doc');
    if (!tbody || !addBtn) return;

    function reindex() {
      tbody.querySelectorAll('.doc-row').forEach(function (row, i) {
        row.querySelector('.doc-index').textContent = i + 1;
      });
    }

    function newRow() {
      var row = document.createElement('tr');
      row.className = 'doc-row';
      row.innerHTML =
        '<td class="doc-index"></td>' +
        '<td><input type="hidden" name="doc_id[]" value=""><input class="form-control" type="text" name="doc_group[]" placeholder="e.g. FY 2025-26"></td>' +
        '<td><input class="form-control" type="text" name="doc_date[]" placeholder="16.01.2025"></td>' +
        '<td><input class="form-control" type="text" name="doc_title[]" placeholder="Document name"></td>' +
        '<td><textarea class="form-control" name="doc_description[]" rows="2" placeholder="Optional description"></textarea></td>' +
        '<td><input class="form-control mb-1" type="file" name="doc_file[]" accept=".pdf,.jpg,.jpeg,.png,.webp">' +
        '<input class="form-control" type="text" name="doc_link[]" placeholder="or paste a link"></td>' +
        '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-doc" title="Remove"><i class="fa fa-trash"></i></button></td>';
      return row;
    }

    addBtn.addEventListener('click', function () {
      tbody.appendChild(newRow());
      reindex();
    });

    tbody.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn-remove-doc');
      if (!btn) return;
      btn.closest('.doc-row').remove();
      reindex();
    });

    // Start with one blank row when the page has no documents yet.
    if (!tbody.querySelectorAll('.doc-row').length) {
      tbody.appendChild(newRow());
      reindex();
    }
  })();
</script>
@endif
