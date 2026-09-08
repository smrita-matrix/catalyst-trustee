{{-- The fields for a blog post, shared by Add and Edit. --}}
@php $b = $blog ?? null; @endphp

<div class="row g-4">

  <div class="col-lg-8">
    <label class="form-label">Title <span class="txt-danger">*</span></label>
    <input type="text" name="title" class="form-control" required
           value="{{ old('title', $b->title ?? '') }}"
           placeholder="e.g. Public InvITs Drive the Next Phase of Road Infrastructure Financing">
    @if ($b)
    <small class="text-secondary d-block mt-1">
      Currently at <b>/blog/{{ $b->slug }}</b>. Renaming the post changes that address.
    </small>
    @endif
  </div>

  <div class="col-lg-4">
    <label class="form-label">Written by</label>
    <input type="text" name="author" class="form-control"
           value="{{ old('author', $b->author ?? '') }}" placeholder="e.g. Catalyst Trusteeship">
  </div>

  <div class="col-lg-4">
    <label class="form-label">Published on</label>
    <input type="date" name="published_on" class="form-control"
           value="{{ old('published_on', optional($b?->published_on)->format('Y-m-d')) }}">
    <small class="text-secondary">Posts are listed newest first.</small>
  </div>

  <div class="col-lg-4">
    <label class="form-label">Picture</label>
    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
    <small class="text-secondary">Shown at the top of the post and on the listing. Up to 8MB.</small>
    @if ($b?->image)
    <div class="mt-2"><img src="{{ $b->image_url }}" alt="" style="width:180px; border-radius:8px;"></div>
    @endif
  </div>

  <div class="col-lg-4">
    <label class="form-label">Order</label>
    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $b->sort_order ?? 0) }}">
    <small class="text-secondary">Only used to break a tie between posts published the same day.</small>
  </div>

  <div class="col-12">
    <label class="form-label">Summary</label>
    <textarea name="excerpt" class="form-control" rows="2"
              placeholder="The few lines shown on the listing. Leave blank to use the opening of the post.">{{ old('excerpt', $b->excerpt ?? '') }}</textarea>
  </div>

  <div class="col-12">
    <label class="form-label" for="editor">The post <span class="txt-danger">*</span></label>
    {{-- "editor" is the id the dashboard's text editor attaches itself to, so
         the post is written with headings, lists and links rather than typed
         as HTML. --}}
    <textarea name="body" id="editor" class="form-control" rows="18" required>{{ old('body', $b->body ?? '') }}</textarea>
    <small class="text-secondary d-block mt-1">
      Use the toolbar for headings, lists, bold and links, the same as in Word.
    </small>
  </div>

  <div class="col-12">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="status" id="status" value="1"
             {{ old('status', $b->status ?? 1) ? 'checked' : '' }}>
      <label class="form-check-label" for="status">Show this post on the website</label>
    </div>
  </div>

</div>
