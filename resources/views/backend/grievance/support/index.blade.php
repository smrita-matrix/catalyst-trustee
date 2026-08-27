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
            <div class="col-6"><h4>Contact for Support</h4></div>
            <div class="col-6">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                </a></li>
                <li class="breadcrumb-item active">Contact for Support</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">

                @if(session('message'))
                  <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                @if($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                  </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Grievance</li>
                    <li class="breadcrumb-item active">Contact for Support</li>
                  </ol></nav>
                </div>

                <div class="alert alert-light border">
                  <span class="text-secondary">
                    <b>Contact for Support</b> in the website menu opens a PDF straight away — it has no page of its own.
                    Upload that PDF here. Until one is uploaded the menu link does nothing.
                  </span>
                </div>

                <div class="card border">
                  <div class="card-header py-2"><b>Current PDF</b></div>
                  <div class="card-body">
                    @if(optional($content)->support_pdf)
                      <p class="mb-2">
                        <a href="{{ asset('grievance/documents/'.$content->support_pdf) }}" target="_blank" rel="noopener noreferrer" class="btn btn-light">
                          <i class="fa fa-file-pdf-o"></i> View current PDF
                        </a>
                        <span class="badge badge-light-success ms-2">Live in the menu</span>
                      </p>
                      <p class="text-secondary small mb-3">{{ $content->support_pdf }}</p>
                      <form action="{{ route('grievance-support.destroy') }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Remove this PDF? The menu link will stop working until a new one is uploaded.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Remove PDF</button>
                      </form>
                    @else
                      <span class="badge badge-light-danger">No PDF uploaded — the menu link does nothing</span>
                    @endif
                  </div>
                </div>

                <div class="card border mt-4">
                  <div class="card-header py-2"><b>{{ optional($content)->support_pdf ? 'Replace PDF' : 'Upload PDF' }}</b></div>
                  <div class="card-body">
                    <form action="{{ route('grievance-support.update') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="row g-4">
                        <div class="col-lg-8">
                          <label class="form-label">Support PDF <span class="txt-danger">*</span></label>
                          <input class="form-control" type="file" name="support_pdf" accept=".pdf" required>
                          <small class="text-secondary d-block mt-1">
                            <i class="fa fa-info-circle"></i> PDF only, max 20 MB. It opens in a new tab when a visitor clicks the menu item.
                          </small>
                        </div>
                        <div class="col-12">
                          <button type="submit" class="btn btn-primary px-5 radius-30">
                            {{ optional($content)->support_pdf ? 'Replace PDF' : 'Upload PDF' }}
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
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
