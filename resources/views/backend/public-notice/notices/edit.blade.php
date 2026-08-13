<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>Edit Notice</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('notices.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Notice</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header"><h4>Edit Notice</h4></div>
                    <div class="card-body">
                        <form class="needs-validation custom-input banner-form" novalidate action="{{ route('notices.update', $notice->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label class="form-label">Section <span class="txt-danger">*</span></label>
                                    <select class="form-control" name="section">
                                        @foreach (\App\Models\Notice::SECTIONS as $val => $label)
                                            <option value="{{ $val }}" {{ old('section', $notice->section) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Title <span class="txt-danger">*</span></label>
                                    <input class="form-control" type="text" name="title" value="{{ old('title', $notice->title) }}" placeholder="Company / notice name" required>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Period <span class="text-secondary">(date pill / month group — e.g. "September 2025")</span></label>
                                    <input class="form-control" type="text" name="period" value="{{ old('period', $notice->period) }}" placeholder="e.g. September 2025">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Auction Date <span class="text-secondary">(Auction section only — e.g. 16.01.2025)</span></label>
                                    <input class="form-control" type="text" name="notice_date" value="{{ old('notice_date', $notice->notice_date) }}" placeholder="16.01.2025">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Order</label>
                                    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $notice->sort_order) }}" placeholder="0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description <span class="text-secondary">(Auction section only — property/notice text)</span></label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Property / notice description">{{ old('description', $notice->description) }}</textarea>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Document (PDF) <span class="text-secondary">(upload)</span></label>
                                    <input class="form-control" type="file" name="document_file" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                    @if ($notice->document_url)
                                        <div class="mt-2"><a href="{{ $notice->document_url }}" target="_blank" class="btn btn-sm btn-light"><i class="fa fa-file-pdf-o"></i> Current document</a> <small class="text-muted">— upload a new one to replace</small></div>
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ (int) $notice->status === 1 ? 'selected' : '' }}>Shown</option>
                                        <option value="0" {{ (int) $notice->status === 0 ? 'selected' : '' }}>Hidden</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                                <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-5" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- footer start-->
        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')
</body>

</html>
