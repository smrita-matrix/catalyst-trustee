{{-- Shared add/edit body for one job opening. --}}
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
  </div>
@endif

<div class="row g-4">

  <div class="col-12">
    <label class="form-label">Job Title <span class="txt-danger">*</span></label>
    <input class="form-control" type="text" name="title" value="{{ old('title', $opening->title) }}" required
           placeholder="e.g. Manager/ Senior Manager - Debenture Trustee">
    <small class="text-secondary">This also appears in the "Position Applying For" dropdown on the form.</small>
  </div>

  <div class="col-lg-3">
    <label class="form-label">Experience</label>
    <input class="form-control" type="text" name="experience" value="{{ old('experience', $opening->experience) }}" placeholder="e.g. 0-3 Years">
  </div>

  <div class="col-lg-3">
    <label class="form-label">Vacancies</label>
    <input class="form-control" type="text" name="vacancies" value="{{ old('vacancies', $opening->vacancies) }}" placeholder="e.g. 7">
  </div>

  <div class="col-lg-3">
    <label class="form-label">Qualification</label>
    <input class="form-control" type="text" name="qualification" value="{{ old('qualification', $opening->qualification) }}" placeholder="e.g. CS, BA-LLB, MBA">
  </div>

  <div class="col-lg-3">
    <label class="form-label">Location</label>
    <input class="form-control" type="text" name="location" value="{{ old('location', $opening->location) }}" placeholder="e.g. Lower Parel, Mumbai">
  </div>

  <div class="col-12">
    <label class="form-label">Job Description</label>
    <textarea class="form-control" name="description" rows="6" placeholder="What the role involves">{{ old('description', $opening->description) }}</textarea>
    <small class="text-secondary">Press Enter for a new line — line breaks are kept on the website.</small>
  </div>

  <div class="col-lg-3">
    <label class="form-label">Display Order</label>
    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $opening->sort_order ?? 0) }}">
    <small class="text-secondary">Lower numbers come first.</small>
  </div>

  <div class="col-lg-3">
    <label class="form-label d-block">Status</label>
    <div class="form-check form-switch mt-2">
      <input type="checkbox" name="status" value="1" class="form-check-input" id="status-toggle"
        {{ old('status', $opening->status ?? 1) ? 'checked' : '' }}>
      <label class="form-check-label" for="status-toggle">Show on the website</label>
    </div>
  </div>

</div>
