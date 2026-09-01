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

  {{-- ---------------- Life at Catalyst stories ---------------- --}}
  @php
    $stories = old('life_stories', $content->life_stories ?: [['title' => '', 'text' => '', 'link' => '']]);
  @endphp
  <div class="col-12">
    <div class="card border">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <b>Life at Catalyst &mdash; Stories</b>
        <button type="button" class="btn btn-outline-primary btn-sm" id="add-story"><i class="fa fa-plus"></i> Add Story</button>
      </div>
      <div class="card-body">

        <div id="stories-list">
          @foreach ($stories as $i => $story)
          <div class="border rounded p-3 mb-3 story-row" style="background:#fbfbfc;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge bg-light text-dark story-number">Story {{ $i + 1 }}</span>
              <button type="button" class="btn btn-outline-danger btn-sm btn-remove-story"><i class="fa fa-trash"></i> Remove</button>
            </div>
            <div class="mb-2">
              <label class="form-label">Title</label>
              <input class="form-control" type="text" name="life_stories[{{ $i }}][title]"
                     value="{{ $story['title'] ?? '' }}" placeholder="e.g. Celebrating Together, Creating Memories">
            </div>
            <div class="mb-2">
              <label class="form-label">Text</label>
              <textarea class="form-control" name="life_stories[{{ $i }}][text]" rows="5"
                        placeholder="Leave a blank line between paragraphs.">{{ $story['text'] ?? '' }}</textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">Photos</label>
              <input class="form-control story-images" type="file" name="life_stories[{{ $i }}][images][]"
                     accept=".jpg,.jpeg,.png,.webp" multiple>
              <small class="text-secondary d-block mt-1">
                <i class="fa fa-info-circle"></i>
                Choose several at once. JPG, PNG or WebP, max 4 MB each. These are shown on the page under the story.
              </small>

              @if(!empty($story['images']))
              <div class="d-flex flex-wrap gap-2 mt-2 story-existing">
                @foreach($story['images'] as $img)
                <div class="border rounded p-1 text-center" style="background:#f7f8fa; width:118px;">
                  <img src="{{ asset('career-uploads/life/'.$img) }}" alt=""
                       style="width:100%; height:78px; object-fit:cover; border-radius:4px; display:block;">
                  <input type="hidden" name="life_stories[{{ $i }}][existing_images][]" value="{{ $img }}">
                  <label class="d-block mt-1" style="font-size:11px; cursor:pointer;">
                    <input type="checkbox" name="life_stories[{{ $i }}][remove_images][]" value="{{ $img }}">
                    Remove
                  </label>
                </div>
                @endforeach
              </div>
              @endif
            </div>

            <div>
              <label class="form-label">Album Link <span class="text-secondary">(optional)</span></label>
              <input class="form-control" type="text" name="life_stories[{{ $i }}][link]"
                     value="{{ $story['link'] ?? '' }}" placeholder="https://drive.google.com/...">
              <small class="text-secondary">
                Kept for your own reference only &mdash; it is no longer shown on the website, since the photos
                above are displayed on the page itself.
              </small>
            </div>
          </div>
          @endforeach
        </div>

        <small class="text-secondary">Empty stories are ignored, so a blank row does no harm.</small>
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

<script>
  // Stories repeater. Rows are numbered so PHP receives each story's title,
  // text and link together, so every add or remove renumbers the list.
  document.addEventListener('DOMContentLoaded', function () {
    var list = document.getElementById('stories-list');
    if (!list) { return; }

    function renumber() {
      list.querySelectorAll('.story-row').forEach(function (row, i) {
        row.querySelector('.story-number').textContent = 'Story ' + (i + 1);
        row.querySelectorAll('[name]').forEach(function (el) {
          el.name = el.name.replace(/life_stories\[\d*\]/, 'life_stories[' + i + ']');
        });
      });
    }

    var addBtn = document.getElementById('add-story');
    if (addBtn) {
      addBtn.addEventListener('click', function () {
        var row = list.querySelector('.story-row').cloneNode(true);
        row.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });

        // A new story must not inherit the photos of the one it was copied from.
        row.querySelectorAll('.story-existing').forEach(function (el) { el.remove(); });

        list.appendChild(row);
        renumber();
        row.querySelector('input').focus();
      });
    }

    list.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn-remove-story');
      if (!btn) { return; }

      if (list.querySelectorAll('.story-row').length > 1) {
        btn.closest('.story-row').remove();
      } else {
        btn.closest('.story-row').querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
      }
      renumber();
    });
  });
</script>
