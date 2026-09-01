{{-- Shared add/edit body for a News & Media card. --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
  </div>
@endif

<div class="row g-4">

  <div class="col-lg-8">
    <label class="form-label">Title <span class="txt-danger">*</span></label>
    <input class="form-control" type="text" name="title" value="{{ old('title', $item->title) }}" required
           placeholder="Headline shown on the card">
  </div>

  <div class="col-lg-4">
    <label class="form-label">Category</label>
    <input class="form-control" type="text" name="category" value="{{ old('category', $item->category) }}" placeholder="e.g. Press Release">
    <small class="text-secondary">The small badge on the image. Leave blank to hide it.</small>
  </div>

  <div class="col-12">
    <label class="form-label">Write-up</label>
    <textarea class="form-control" name="description" rows="6"
              placeholder="The text shown under the headline on the card.">{{ old('description', $item->description) }}</textarea>
    <small class="text-secondary">
      <i class="fa fa-info-circle"></i>
      Optional. Leave a blank line between paragraphs. Leave empty to show only the headline.
    </small>
  </div>

  <div class="col-lg-6">
    <label class="form-label">Card Image</label>
    <input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
    <small class="text-secondary d-block mt-1">
      <i class="fa fa-info-circle"></i> Recommended <b>800 x 520 px</b> - WebP, JPG or PNG, max 4 MB. Cropped to fill the card.
    </small>
    @if($item->image_url)
      <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
        <img src="{{ $item->image_url }}" alt="current image" style="max-height:110px; border-radius:6px; display:block;">
      </div>
    @endif
  </div>

  <div class="col-lg-6">
    <label class="form-label">Read More — PDF</label>
    <input class="form-control" type="file" name="pdf_file" accept=".pdf">
    <small class="text-secondary d-block mt-1"><i class="fa fa-info-circle"></i> PDF only, max 20 MB.</small>
    @if($item->pdf_file)
      <div class="mt-2">
        <a href="{{ asset('news-media-uploads/pdf/'.$item->pdf_file) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light">
          <i class="fa fa-file-pdf-o"></i> Current PDF
        </a>
        <small class="text-muted">- upload a new one to replace</small>
      </div>
    @endif
  </div>

  <div class="col-lg-6">
    <label class="form-label">Read More — External Link</label>
    <input class="form-control" type="url" name="link" value="{{ old('link', $item->link) }}" placeholder="https://...">
    <small class="text-secondary">Used only when no PDF is uploaded. Leave both blank to hide the Read More link.</small>
  </div>

  <div class="col-lg-3">
    <label class="form-label">Display Order</label>
    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
    <small class="text-secondary">Lower numbers come first.</small>
  </div>

  <div class="col-lg-3">
    <label class="form-label d-block">Status</label>
    <div class="form-check form-switch mt-2">
      <input type="checkbox" name="status" value="1" class="form-check-input" id="status-toggle"
        {{ old('status', $item->status ?? 1) ? 'checked' : '' }}>
      <label class="form-check-label" for="status-toggle">Show on the website</label>
    </div>
  </div>

</div>
