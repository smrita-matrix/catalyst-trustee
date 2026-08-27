{{-- Shared add/edit body for a policy page. --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

@php
  $sections = old('sections', $page->sections ?: [['heading' => '', 'body' => '']]);
@endphp

<div class="row g-4">

  {{-- ---------------- Page basics ---------------- --}}
  <div class="col-12">
    <div class="card border mt-2">
      <div class="card-header py-2"><b>Page Details</b></div>
      <div class="card-body">
        <div class="row g-4">

          <div class="col-lg-4">
            <label class="form-label">Page Title <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="title" value="{{ old('title', $page->title) }}" placeholder="e.g. Privacy Policy" required>
          </div>

          <div class="col-lg-4">
            <label class="form-label">Web Address</label>
            <div class="input-group">
              <span class="input-group-text">{{ rtrim(url('/'), '/') }}/</span>
              <input class="form-control" type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="privacy-policy">
            </div>
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Leave blank and it is made from the title. Small letters, numbers and hyphens only.
            </small>
          </div>

          <div class="col-lg-4">
            <label class="form-label">Breadcrumb Label</label>
            <input class="form-control" type="text" name="breadcrumb_child" value="{{ old('breadcrumb_child', $page->breadcrumb_child) }}" placeholder="defaults to the page title">
          </div>

          <div class="col-lg-4">
            <label class="form-label">Banner Background Image</label>
            <input class="form-control" type="file" name="banner_image" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Recommended <b>1500 &times; 976 px</b> &mdash; WebP, JPG or PNG, max 4 MB.
            </small>
            @if($page->banner_image)
              <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
                <img src="{{ $page->banner_url }}" alt="banner" style="max-height:110px; border-radius:6px; display:block;">
              </div>
            @endif
          </div>

          <div class="col-lg-4">
            <label class="form-label">Last Updated On</label>
            <input class="form-control" type="date" name="effective_on" value="{{ old('effective_on', optional($page->effective_on)->format('Y-m-d')) }}">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Shown at the top of the page. Leave blank to hide it.
            </small>
          </div>

          <div class="col-lg-4">
            <label class="form-label">Display Order</label>
            <input class="form-control" type="number" name="sort_order" min="0" value="{{ old('sort_order', $page->sort_order ?? 0) }}">
            <small class="text-secondary d-block mt-1">
              <i class="fa fa-info-circle"></i>
              Lower numbers appear first in the footer.
            </small>
          </div>

          <div class="col-12">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="status" value="1" id="status"
                     {{ old('status', $page->exists ? $page->status : 1) ? 'checked' : '' }}>
              <label class="form-check-label" for="status">Published &mdash; visible on the website</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="show_in_footer" value="1" id="show_in_footer"
                     {{ old('show_in_footer', $page->exists ? $page->show_in_footer : 1) ? 'checked' : '' }}>
              <label class="form-check-label" for="show_in_footer">Show a link to this page in the website footer</label>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- ---------------- Intro ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2"><b>Opening Paragraph</b>
        <small class="text-secondary d-block">Optional. Appears above the sections.</small>
      </div>
      <div class="card-body">
        <textarea class="form-control" name="intro_text" rows="3" placeholder="e.g. This policy explains what information we collect when you use our website...">{{ old('intro_text', $page->intro_text) }}</textarea>
      </div>
    </div>
  </div>

  {{-- ---------------- Sections repeater ---------------- --}}
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <b>Page Sections</b>
        <button type="button" class="btn btn-outline-primary btn-sm" id="add-section"><i class="fa fa-plus"></i> Add Section</button>
      </div>
      <div class="card-body">

        <div id="sections-list">
          @foreach ($sections as $i => $section)
          <div class="border rounded p-3 mb-3 section-row" style="background:#fbfbfc;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge bg-light text-dark section-number">Section {{ $i + 1 }}</span>
              <button type="button" class="btn btn-outline-danger btn-sm btn-remove-section"><i class="fa fa-trash"></i> Remove</button>
            </div>
            <div class="mb-2">
              <label class="form-label">Section Heading</label>
              <input class="form-control" type="text" name="sections[{{ $i }}][heading]"
                     value="{{ $section['heading'] ?? '' }}" placeholder="e.g. Information We Collect">
            </div>
            <div>
              <label class="form-label">Section Text</label>
              <textarea class="form-control" name="sections[{{ $i }}][body]" rows="5"
                        placeholder="Write the paragraphs here.">{{ $section['body'] ?? '' }}</textarea>
            </div>
          </div>
          @endforeach
        </div>

        <div class="alert alert-light border mb-0">
          <b>How the text is displayed</b>
          <ul class="mb-0 mt-1" style="padding-left:18px;">
            <li>Leave a <b>blank line</b> between paragraphs and each becomes its own paragraph.</li>
            <li>Start lines with a hyphen and a space to make a bulleted list.</li>
            <li>Links are allowed, for example &lt;a href="https://catalysttrustee.com"&gt;our website&lt;/a&gt;</li>
            <li>Empty sections are ignored, so a blank row does no harm.</li>
          </ul>
        </div>

      </div>
    </div>
  </div>

</div>

<script>
  // Sections repeater. Rows are numbered so PHP receives heading and text as
  // pairs, so every add or remove renumbers the whole list.
  document.addEventListener('DOMContentLoaded', function () {
    var list = document.getElementById('sections-list');
    if (!list) { return; }

    function renumber() {
      list.querySelectorAll('.section-row').forEach(function (row, i) {
        row.querySelector('.section-number').textContent = 'Section ' + (i + 1);
        row.querySelectorAll('[name]').forEach(function (el) {
          el.name = el.name.replace(/sections\[\d*\]/, 'sections[' + i + ']');
        });
      });
    }

    var addBtn = document.getElementById('add-section');
    if (addBtn) {
      addBtn.addEventListener('click', function () {
        var row = list.querySelector('.section-row').cloneNode(true);
        row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
        list.appendChild(row);
        renumber();
        row.querySelector('input').focus();
      });
    }

    list.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn-remove-section');
      if (!btn) { return; }

      if (list.querySelectorAll('.section-row').length > 1) {
        btn.closest('.section-row').remove();
      } else {
        // Never leave the admin with nothing to type into.
        btn.closest('.section-row').querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
      }
      renumber();
    });
  });
</script>
