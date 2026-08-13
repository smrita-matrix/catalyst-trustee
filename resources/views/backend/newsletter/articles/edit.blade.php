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
                <div class="col-6"><h4>Edit Article</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Article</li>
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
                    <div class="card-header"><h4>Edit Article</h4>
                        <p class="mt-1 mb-0"><span class="badge badge-light-warning"><i class="fa fa-info-circle"></i> Cover Image: <b>WebP only</b>, max <b>2 MB</b> &nbsp;|&nbsp; PDF: max <b>25 MB</b></span></p></div>
                    <div class="card-body">
                        <form class="needs-validation custom-input banner-form" novalidate action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <label class="form-label">Year <span class="txt-danger">*</span></label>
                                    <input class="form-control" type="text" name="year" value="{{ old('year', $article->year) }}" placeholder="2025 (or Archive)">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Title (label) <span class="txt-danger">*</span></label>
                                    <input class="form-control" type="text" name="title" value="{{ old('title', $article->title) }}" placeholder="e.g. October" required>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Order</label>
                                    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $article->sort_order) }}" placeholder="0">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Cover Image <span class="text-secondary">(WebP only, ≤2 MB)</span></label>
                                    <input class="form-control" type="file" name="image" accept=".webp">
                                    <div class="img-preview mt-2">@if($article->image_url)<img src="{{ $article->image_url }}" alt="cover" style="max-height:120px;">@endif</div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">PDF file <span class="text-secondary">(PDF only, ≤25 MB)</span></label>
                                    <input class="form-control" type="file" name="pdf_file" accept=".pdf">
                                    @if($article->pdf_file)<div class="mt-2"><a href="{{ $article->pdf_url }}" target="_blank" class="btn btn-sm btn-light"><i class="fa fa-file-pdf-o"></i> Current PDF</a> <small class="text-muted">— upload to replace</small></div>@endif
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ (int)$article->status === 1 ? 'selected' : '' }}>Shown</option>
                                        <option value="0" {{ (int)$article->status === 0 ? 'selected' : '' }}>Hidden</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
